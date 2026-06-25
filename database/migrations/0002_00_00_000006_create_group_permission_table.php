<?php

use App\Models\Group;
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
        Schema::create('group_permission', function (Blueprint $table) {
            $table->string('permission_id');
            $table->foreign('permission_id')
                ->constrained()
                ->references('id')
                ->on('permissions')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Group::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_permission');
    }
};
