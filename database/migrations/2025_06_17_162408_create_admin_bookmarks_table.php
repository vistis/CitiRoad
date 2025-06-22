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

        Schema::create('admin_bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('report_id');
            $table->foreign('report_id')->references('id')->on('reports');
            $table->string('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_bookmarks');
    }
};
