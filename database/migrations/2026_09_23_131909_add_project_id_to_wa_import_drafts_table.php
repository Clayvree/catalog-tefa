<?php

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
        Schema::table('wa_import_drafts', function (Blueprint $table) {
            $table->uuid('project_id')->nullable()->after('tefa_unit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wa_import_drafts', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
};
