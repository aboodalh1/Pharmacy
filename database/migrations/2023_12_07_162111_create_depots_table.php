<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_id');
            $table->unsignedBigInteger('depot_id');
            $table->unsignedBigInteger('classification_id');
            $table->integer('quintity');
            $table->date('date_of_end');
            $table->float('price');
            $table->string('image')->nullable();
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('depot_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('classification_id')->references('id')->on('classifications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depots');
    }
};
