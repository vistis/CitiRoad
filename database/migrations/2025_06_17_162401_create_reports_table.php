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

        Schema::create('reports', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->text('title');
            $table->enum('status', ["Reviewing","Investigating","Rejected","Resolving","Resolved"]);
            $table->integer('province_id')->comment('One-to-one');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->text('address');
            $table->longText('description');
            $table->string('citizen_id')->nullable()->comment('Associate report to a citizen. One report belong to one citizen only. One citizen can have many reports.');
            $table->foreign('citizen_id')->references('id')->on('citizens');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('officers');
            $table->text('remark')->nullable()->comment('Remark left by officers when they update status');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
