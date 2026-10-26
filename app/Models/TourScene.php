<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourScene extends Model
{
    protected $fillable = [
        'slug', 'name', 'image_path', 'image_width', 'levels', 'face_size',
        'initial_yaw', 'initial_pitch', 'initial_fov', 'sort_order',
    ];

    protected $casts = [
        'levels' => 'array',
        'initial_yaw' => 'float',
        'initial_pitch' => 'float',
        'initial_fov' => 'float',
    ];

    public function hotspots(): HasMany
    {
        return $this->hasMany(TourHotspot::class, 'tour_scene_id');
    }

    public function isTiled(): bool
    {
        return $this->image_path === null;
    }

    // Scene as the viewer (public tour and admin editor) expects it.
    public function toViewerArray(): array
    {
        $data = [
            'id' => $this->slug,
            'dbId' => $this->id,
            'name' => $this->name,
            'initialViewParameters' => [
                'yaw' => $this->initial_yaw,
                'pitch' => $this->initial_pitch,
                'fov' => $this->initial_fov,
            ],
            'linkHotspots' => $this->hotspots->map(fn (TourHotspot $h) => [
                'id' => $h->id,
                'yaw' => $h->yaw,
                'pitch' => $h->pitch,
                'rotation' => $h->rotation,
                'target' => $h->target?->slug,
                'targetId' => $h->target_scene_id,
                'label' => $h->label,
            ])->filter(fn ($h) => $h['target'] !== null)->values()->all(),
            'infoHotspots' => [],
        ];

        if ($this->isTiled()) {
            $data['levels'] = $this->levels;
            $data['faceSize'] = $this->face_size;
        } else {
            $data['imageUrl'] = route('tour.image', ['scene' => $this->id, 'v' => $this->updated_at?->timestamp]);
            $data['imageWidth'] = $this->image_width;
        }

        return $data;
    }
}
