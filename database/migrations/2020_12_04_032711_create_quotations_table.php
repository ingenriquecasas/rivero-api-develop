<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id')->unsigned();
            $table->string('payment_method')->default('credit');
            $table->string('desired_for')->default('me'); // me | other
            $table->string('use_purpose')->default('personal'); // personal | commercial
            $table->string('expectation')->nullable();
            $table->integer('color')->nullable();
            $table->boolean('sell_car')->default(false);
            $table->decimal('entry_percentage')->nullable();
            $table->decimal('entry_payment')->nullable();
            $table->integer('months')->nullable();
            $table->decimal('monthly_payment')->nullable();
            $table->integer('warranty_id')->default(3);
            $table->decimal('warranty_monthly')->nullable();
            $table->json('quote')->nullable();

            $table->timestamps();
            // TODO: Add foreign to improve database performance
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
