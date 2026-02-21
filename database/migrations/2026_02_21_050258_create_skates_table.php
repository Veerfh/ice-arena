<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skates', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('brand');
            $table->integer('size');
            $table->integer('quantity');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('skates');
    }
};