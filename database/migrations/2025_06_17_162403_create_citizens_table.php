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

        Schema::create('citizens', function (Blueprint $table) {
            $table->bigInteger('id')->primary()->comment('National ID');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->enum('status', ["Pending","Approved","Restricted","Rejected"]);
            $table->integer('province_id');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->text('address');
            $table->date('date_of_birth');
            $table->string('profile_picture_path')->comment('Image URL');
            $table->enum('gender', ["Male","Female","Prefer Not to Say"]);
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
        Schema::dropIfExists('citizens');
    }
};
