<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tefa_unit_id');
            $table->uuid('user_id')->nullable(); // nullable: bisa dari guest
            $table->uuid('project_id')->nullable(); // terhubung ke pipeline WA
            $table->string('customer_name');
            $table->string('customer_contact')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->enum('status', ['pending', 'processed', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete();

            $table->index(['tefa_unit_id', 'status']);
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
