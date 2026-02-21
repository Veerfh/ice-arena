<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->integer('hours');
            $table->foreignId('skate_id')->nullable()->constrained();
            $table->integer('skate_size')->nullable();
            $table->integer('total_amount');
            $table->boolean('is_paid')->default(false);
            $table->string('payment_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};