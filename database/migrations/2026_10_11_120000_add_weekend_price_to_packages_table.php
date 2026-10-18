<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Optional weekend price; fills the swimming packages only if still empty.
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('weekend_price', 10, 2)->nullable()->after('price');
        });

        $weekendRates = [
            'Day Swim'           => 14500,
            'Night Swim'         => 24000,
            '22 Hours Overnight' => 30000,
        ];

        foreach ($weekendRates as $name => $rate) {
            DB::table('packages')
                ->where('name', $name)
                ->whereNull('weekend_price')
                ->update(['weekend_price' => $rate]);
        }
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('weekend_price');
        });
    }
};
