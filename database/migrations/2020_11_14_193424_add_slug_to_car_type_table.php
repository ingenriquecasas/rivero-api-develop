<?php

use App\Models\CarType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToCarTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referencia_tipo_vehiculo', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        $types = CarType::get();

        $types->each(function($type) {
            $type->slug = Str::slug($type->valor);
            $type->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referencia_tipo_vehiculo', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
