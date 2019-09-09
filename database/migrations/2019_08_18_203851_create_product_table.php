<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    private $table = 'product';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function(Blueprint $table) {
            $table->primary('id');
            $table->index('cart_id');

            $table->uuid('id');
            $table->uuid('cart_id')->nullable();
            $table->string('name', 100);
            $table->float('price', 15, 2);
            $table->string('currency_iso_code', 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table);
    }
}
