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
        Schema::create('permissions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->text('description')->nullable();
            $table->string('module');
            $table->string('feature');
            $table->string('action');
            $table->integer('feature_order')->default(0);
            $table->integer('module_order')->default(0);
            $table->boolean('is_menu')->default(false);
            $table->string('module_icon')->nullable();
            $table->string('feature_icon')->nullable();
            $table->timestamps();
            $table->index(['module', 'feature', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
