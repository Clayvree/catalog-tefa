<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_worker', function (Blueprint $table) {
            $table->text('member_task_note')->nullable()->after('worker_profile_id');
        });
    }

    public function down(): void
    {
        Schema::table('task_worker', function (Blueprint $table) {
            $table->dropColumn('member_task_note');
        });
    }
};
