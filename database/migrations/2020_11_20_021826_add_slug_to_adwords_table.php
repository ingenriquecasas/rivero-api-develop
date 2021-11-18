<?php

use App\Models\Ad;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToAdwordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adwords', function (Blueprint $table) {
            $table->string('slug')
                ->nullable()
                ->after('imagen');
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
        Schema::table('adwords', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'created_at',
                'updated_at',
            ]);
        });
    }
}
