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

        Schema::create('officer_bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('report_id');
            $table->foreign('report_id')->references('id')->on('reports');
            $table->bigInteger('officer_id');
            $table->foreign('officer_id')->references('id')->on('officers');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_bookmarks');
    }
};
