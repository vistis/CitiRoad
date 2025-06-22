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
        Schema::disableForeignKeyConstraints();

        Schema::create('report_images', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->enum('type', ["Before","After"]);
            $table->string('image_path');
            $table->integer('report_id');
            $table->foreign('report_id')->references('id')->on('reports');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_images');
    }
};
