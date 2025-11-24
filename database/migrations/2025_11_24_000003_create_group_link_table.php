<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_link', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('link_id')->constrained('links')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['group_id', 'link_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_link');
    }
};