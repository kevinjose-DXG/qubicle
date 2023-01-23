<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_masters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->float('mrp')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('no_of_flyers')->nullable();
            $table->integer('no_of_images_per_flyers')->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('plan_masters');
    }
}
