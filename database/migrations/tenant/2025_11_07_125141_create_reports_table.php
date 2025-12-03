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
        Schema::create('reports', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('type'); // inventory, stock_movement, etc.
    $table->string('date_range');
    $table->string('format'); // pdf, excel, csv
    $table->string('file_path');
    $table->boolean('is_scheduled')->default(false);
    $table->string('frequency')->nullable(); // daily, weekly
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
