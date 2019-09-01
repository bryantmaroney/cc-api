<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("customer_id");
            $table->integer("product_id");
            $table->double("shipping_amount", 8, 2);
            $table->double("amount", 8, 2);
            $table->string("transaction_id", 200);
            $table->tinyInteger("was_declined");
            $table->tinyInteger("sent_to_woo");
            $table->timestamps();
            $table->index(["customer_id", "product_id", "was_declined", "sent_to_woo"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
