<?php

namespace App\Console\Commands;

use App\Models\TourHotspot;
use App\Models\TourScene;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTour extends Command
{
    protected $signature = 'tour:import {--force : Import even if scenes already exist}';

    protected $description = 'Import the Marzipano scenes from data.js into the database';

    public function handle(): int
    {
        if (TourScene::exists() && !$this->option('force')) {
            $this->warn('Scenes already exist, nothing imported. Use --force to import again.');
            return self::SUCCESS;
        }

        $path = public_path('lordanes-place-360/app-files/data.js');
        if (!is_file($path)) {
            $this->error('data.js not found.');
            return self::FAILURE;
        }

        // data.js is "var APP_DATA = {...};", so cut it down to the JSON.
        $raw = file_get_contents($path);
        $json = substr($raw, strpos($raw, '{'), strrpos($raw, '}') - strpos($raw, '{') + 1);
        $data = json_decode($json, true);
        if (!is_array($data) || empty($data['scenes'])) {
            $this->error('Could not read the scenes from data.js.');
            return self::FAILURE;
        }

        DB::transaction(function () use ($data) {
            $ids = [];
            foreach ($data['scenes'] as $i => $s) {
                $ids[$s['id']] = TourScene::updateOrCreate(['slug' => $s['id']], [
                    'name' => trim($s['name']),
                    'levels' => $s['levels'],
                    'face_size' => $s['faceSize'],
                    'initial_yaw' => $s['initialViewParameters']['yaw'] ?? 0,
                    'initial_pitch' => $s['initialViewParameters']['pitch'] ?? 0,
                    'initial_fov' => $s['initialViewParameters']['fov'] ?? 1.5707963267948966,
                    'sort_order' => $i,
                ])->id;
            }

            // Re-importing replaces the arrows of these scenes instead of doubling them.
            TourHotspot::whereIn('tour_scene_id', $ids)->delete();

            foreach ($data['scenes'] as $s) {
                foreach ($s['linkHotspots'] as $h) {
                    if (!isset($ids[$h['target']])) {
                        continue;
                    }
                    TourHotspot::create([
                        'tour_scene_id' => $ids[$s['id']],
                        'target_scene_id' => $ids[$h['target']],
                        'yaw' => $h['yaw'],
                        'pitch' => $h['pitch'],
                        'rotation' => $h['rotation'] ?? 0,
                    ]);
                }
            }
        });

        $this->info('Imported ' . TourScene::count() . ' scenes and ' . TourHotspot::count() . ' arrows.');
        return self::SUCCESS;
    }
}
