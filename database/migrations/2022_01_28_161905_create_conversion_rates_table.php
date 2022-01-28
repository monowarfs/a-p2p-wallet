<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversionRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversion_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_id')->constrained('currencies');
            $table->foreignId('to_id')->constrained('currencies');
            $table->decimal('rate', 25,10);
            $table->string('provider')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversion_rates');
    }
}
