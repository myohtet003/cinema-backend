<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateGroups = DB::table('seat_locks')
            ->select('showtime_id', 'seat_id', DB::raw('MAX(id) as keep_id'))
            ->groupBy('showtime_id', 'seat_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicateGroups as $group) {
            DB::table('seat_locks')
                ->where('showtime_id', $group->showtime_id)
                ->where('seat_id', $group->seat_id)
                ->where('id', '!=', $group->keep_id)
                ->delete();
        }

        Schema::table('seat_locks', function (Blueprint $table) {
            $table->unique(['showtime_id', 'seat_id'], 'seat_locks_showtime_seat_unique');
        });
    }

    public function down(): void
    {
        Schema::table('seat_locks', function (Blueprint $table) {
            $table->dropUnique('seat_locks_showtime_seat_unique');
        });
    }
};
