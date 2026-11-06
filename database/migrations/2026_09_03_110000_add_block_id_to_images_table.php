<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlockIdToImagesTable extends Migration
{
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->unsignedBigInteger('block_id')->nullable()->after('layout');
        });
    }
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('block_id');
        });
    }
}
