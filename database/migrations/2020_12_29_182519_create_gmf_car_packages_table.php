<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGmfCarPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmf_car_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')
                ->unique();
            $table->json('package')->nullable();
            $table->timestamps();

            // TODO: add foreign keys
            // $table->foreign('car_id')->references('id')->on('catalogo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gmf_car_packages');
    }
}
