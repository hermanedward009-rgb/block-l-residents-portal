<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedTinyInteger('max_occupants')->default(2)->after('allow_multiple_occupants');
        });

        Schema::table('cleaning_schedules', function (Blueprint $table) {
            $table->string('toilet_type', 10)->default('small')->after('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::table('cleaning_schedules', function (Blueprint $table) {
            $table->dropColumn('toilet_type');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('max_occupants');
        });
    }
};