<?php

namespace App\Http\Controllers;

use App\Models\TourScene;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    // The viewer's data file, built from the database.
    public function data()
    {
        $scenes = TourScene::with('hotspots.target')->orderBy('sort_order')->orderBy('id')->get();

        $payload = [
            'scenes' => $scenes->map->toViewerArray()->all(),
            'name' => "LorDane's Place 360",
            'settings' => [
                'mouseViewMode' => 'drag',
                'autorotateEnabled' => true,
                'fullscreenButton' => false,
                'viewControlButtons' => false,
            ],
        ];

        return response('var APP_DATA = ' . json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR) . ';')
            ->header('Content-Type', 'application/javascript; charset=UTF-8')
            ->header('Cache-Control', 'no-cache');
    }

    // Streams an uploaded 360 picture from the tour disk.
    public function image(TourScene $scene)
    {
        $disk = Storage::disk(config('filesystems.tour_disk'));
        abort_unless($scene->image_path && $disk->exists($scene->image_path), 404);

        return response($disk->get($scene->image_path))
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
