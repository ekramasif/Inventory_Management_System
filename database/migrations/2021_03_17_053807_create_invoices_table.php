<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_mail');
            $table->string('company');
            $table->string('address');
            $table->string('item');
            $table->string('product_name');
            $table->string('price');
            $table->integer('quantity');
            $table->string('total');
            $table->string('payment');
            $table->string('due');
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
        Schema::dropIfExists('invoices');
    }
}
