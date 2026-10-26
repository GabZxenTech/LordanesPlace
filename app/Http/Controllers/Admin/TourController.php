<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourHotspot;
use App\Models\TourScene;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TourController extends Controller
{
    private const MAX_WIDTH = 4096;

    public function index()
    {
        $scenes = TourScene::withCount('hotspots')->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.tour', compact('scenes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:120',
            'image' => 'required|file|mimes:jpg,jpeg|max:25600',
        ]);

        [$path, $width] = $this->storeImage($request->file('image'));

        $scene = TourScene::create([
            'slug' => Str::slug($request->name) . '-' . Str::lower(Str::random(6)),
            'name' => trim($request->name),
            'image_path' => $path,
            'image_width' => $width,
            'sort_order' => (int) TourScene::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.tour.show', $scene)->with('success', 'Scene added. Click on the picture to place arrows.');
    }

    // The arrow editor for one scene.
    public function show(TourScene $scene)
    {
        $scene->load('hotspots.target');
        $targets = TourScene::where('id', '!=', $scene->id)->orderBy('sort_order')->orderBy('id')->get(['id', 'name']);

        return view('admin.tour-editor', [
            'scene' => $scene,
            'sceneData' => $scene->toViewerArray(),
            'targets' => $targets,
        ]);
    }

    public function update(Request $request, TourScene $scene)
    {
        $request->validate([
            'name'  => 'required|string|max:120',
            'image' => 'nullable|file|mimes:jpg,jpeg|max:25600',
        ]);

        $data = ['name' => trim($request->name)];

        if ($request->hasFile('image')) {
            [$path, $width] = $this->storeImage($request->file('image'));
            $this->deleteImage($scene);
            $data['image_path'] = $path;
            $data['image_width'] = $width;
            // A new picture replaces the old tiles.
            $data['levels'] = null;
            $data['face_size'] = null;
        }

        $scene->update($data);

        return back()->with('success', 'Scene updated.');
    }

    public function destroy(TourScene $scene)
    {
        $this->deleteImage($scene);
        $scene->delete();

        return redirect()->route('admin.tour.index')->with('success', 'Scene deleted.');
    }

    // Starting view = what the admin is looking at in the editor.
    public function setView(Request $request, TourScene $scene)
    {
        $data = $request->validate([
            'yaw'   => 'required|numeric',
            'pitch' => 'required|numeric',
            'fov'   => 'required|numeric|between:0.3,2.6',
        ]);

        $scene->update([
            'initial_yaw' => $data['yaw'],
            'initial_pitch' => $data['pitch'],
            'initial_fov' => $data['fov'],
        ]);

        return response()->json(['ok' => true]);
    }

    public function storeHotspot(Request $request, TourScene $scene)
    {
        $data = $request->validate([
            'target_scene_id' => 'required|integer|exists:tour_scenes,id|not_in:' . $scene->id,
            'yaw'             => 'required|numeric',
            'pitch'           => 'required|numeric',
            'label'           => 'nullable|string|max:120',
        ]);

        $hotspot = $scene->hotspots()->create($data);

        return response()->json($this->hotspotArray($hotspot));
    }

    public function updateHotspot(Request $request, TourHotspot $hotspot)
    {
        $data = $request->validate([
            'target_scene_id' => 'sometimes|integer|exists:tour_scenes,id|not_in:' . $hotspot->tour_scene_id,
            'yaw'             => 'sometimes|numeric',
            'pitch'           => 'sometimes|numeric',
            'label'           => 'nullable|string|max:120',
        ]);

        $hotspot->update($data);

        return response()->json($this->hotspotArray($hotspot->fresh()));
    }

    public function destroyHotspot(TourHotspot $hotspot)
    {
        $hotspot->delete();

        return response()->json(['ok' => true]);
    }

    private function hotspotArray(TourHotspot $hotspot): array
    {
        $hotspot->load('target');

        return [
            'id' => $hotspot->id,
            'yaw' => $hotspot->yaw,
            'pitch' => $hotspot->pitch,
            'rotation' => $hotspot->rotation,
            'target' => $hotspot->target?->slug,
            'targetId' => $hotspot->target_scene_id,
            'label' => $hotspot->label,
        ];
    }

    // Saves the 360 picture (shrunk to 4096 px wide when GD is available); returns [path, width].
    private function storeImage(UploadedFile $file): array
    {
        $info = @getimagesize($file->getRealPath());
        if (!$info || ($info[2] ?? null) !== IMAGETYPE_JPEG) {
            throw ValidationException::withMessages(['image' => 'Please upload a JPG picture.']);
        }

        [$width, $height] = $info;
        if ($width < 1024 || abs($width / $height - 2) > 0.15) {
            throw ValidationException::withMessages(['image' => 'A 360 picture must be a 2:1 panorama (for example 4096 × 2048 px).']);
        }

        $bytes = file_get_contents($file->getRealPath());
        $gd = function_exists('imagecreatefromjpeg');

        if ($width > self::MAX_WIDTH) {
            if (!$gd || $width > 16000) {
                throw ValidationException::withMessages(['image' => 'This picture is too large. Please resize it to 4096 px wide or smaller.']);
            }

            @ini_set('memory_limit', '768M');
            $src = imagecreatefromjpeg($file->getRealPath());
            $newHeight = (int) round($height * self::MAX_WIDTH / $width);
            $dst = imagecreatetruecolor(self::MAX_WIDTH, $newHeight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, self::MAX_WIDTH, $newHeight, $width, $height);
            ob_start();
            imagejpeg($dst, null, 85);
            $bytes = ob_get_clean();
            imagedestroy($src);
            imagedestroy($dst);
            $width = self::MAX_WIDTH;
        }

        $path = 'tour/' . Str::random(32) . '.jpg';
        Storage::disk(config('filesystems.tour_disk'))->put($path, $bytes);

        return [$path, $width];
    }

    private function deleteImage(TourScene $scene): void
    {
        if ($scene->image_path) {
            Storage::disk(config('filesystems.tour_disk'))->delete($scene->image_path);
        }
    }
}
