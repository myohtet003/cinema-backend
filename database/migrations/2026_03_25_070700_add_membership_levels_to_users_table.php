<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('membership_level')->nullable()->after('membership_joined_at');
            $table->unsignedTinyInteger('membership_discount_percent')->default(0)->after('membership_level');
            $table->unsignedBigInteger('membership_total_spent')->default(0)->after('membership_discount_percent');
        });

        $initial = User::initialMembershipAttributes();

        User::where('is_club_member', true)->update([
            'membership_level' => $initial['membership_level'],
            'membership_discount_percent' => $initial['membership_discount_percent'],
            'membership_total_spent' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'membership_level',
                'membership_discount_percent',
                'membership_total_spent',
            ]);
        });
    }
};
