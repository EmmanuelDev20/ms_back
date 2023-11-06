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
        // Respaldo de migration anterior
        // Schema::create('configs', function (Blueprint $table) {
        //     $table->id();

        //     $table->string('home_image')->nullable();
        //     $table->text('home_description');

        //     $table->string('first_image')->nullable();
        //     $table->string('second_image')->nullable();
        //     $table->string('third_image')->nullable();

        //     $table->text('about_us_description')->nullable();
        //     $table->string('about_image')->nullable();

        //     $table->timestamps();
        // });

        Schema::create('configs', function (Blueprint $table) {
            $table->id();

            $table->string('home_image')->nullable();

            $table->string('first_image')->nullable();
            $table->string('second_image')->nullable();
            $table->string('third_image')->nullable();

            $table->string('about_image')->nullable();

            $table->timestamps();
        });

        Schema::create('config_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('config_id')->unsigned();
            $table->string('locale')->index();

            // Translatable fields
            $table->text('home_description');
            $table->text('about_us_description')->nullable();

            $table->unique(['config_id', 'locale']);

            $table->foreign('config_id')
                  ->references('id')
                  ->on('configs')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('configs');
        Schema::dropIfExists('config_translations');
    }
}
