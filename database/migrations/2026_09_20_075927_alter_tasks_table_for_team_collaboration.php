<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_worker_id']);
            $table->dropIndex(['assigned_worker_id', 'status']);
            $table->renameColumn('assigned_worker_id', 'leader_id');
            
            $table->foreign('leader_id')->references('id')->on('worker_profiles')->nullOnDelete();
            
            $table->text('goals')->nullable()->after('description');
            $table->text('team_notes')->nullable()->after('goals');
            $table->integer('progress_percentage')->default(0)->after('team_notes');
            
            $table->index(['leader_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
            $table->dropIndex(['leader_id', 'status']);
            $table->renameColumn('leader_id', 'assigned_worker_id');
            
            $table->dropColumn(['goals', 'team_notes', 'progress_percentage']);
            
            $table->foreign('assigned_worker_id')->references('id')->on('worker_profiles')->nullOnDelete();
            $table->index(['assigned_worker_id', 'status']);
        });
    }
};
