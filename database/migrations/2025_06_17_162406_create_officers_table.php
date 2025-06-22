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

        Schema::create('officers', function (Blueprint $table) {
            $table->string('id')->primary()->comment('Government ID');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->enum('role', ["Municipality Head","Municipality Deputy"]);
            $table->integer('province_id')->comment('Define the jurisdiction. Reports are sent to the designated municipality.');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->string('profile_picture_path')->comment('Image URL');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->string('remember_token')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
