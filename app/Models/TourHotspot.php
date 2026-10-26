<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourHotspot extends Model
{
    protected $fillable = ['tour_scene_id', 'target_scene_id', 'yaw', 'pitch', 'rotation', 'label'];

    protected $casts = ['yaw' => 'float', 'pitch' => 'float', 'rotation' => 'float'];

    public function scene(): BelongsTo
    {
        return $this->belongsTo(TourScene::class, 'tour_scene_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(TourScene::class, 'target_scene_id');
    }
}
