<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();

            $table->string('home_image')->nullable();
            $table->text('home_description');

            $table->string('first_image')->nullable();
            $table->string('second_image')->nullable();
            $table->string('third_image')->nullable();

            $table->text('about_us_description');
            $table->string('about_image')->nullable();

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
        Schema::dropIfExists('configs');
    }
}
