<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->integer('category_id');             
            $table->string('name');
            $table->string('sub_text');                         
            $table->string('slug')->unique();              
            $table->longText('brief_detail');               
            $table->longText('full_detail');                           
            $table->bigInteger('price');
            $table->integer('stock');
            $table->integer('display')->default(0);
            $table->integer('handling_id')->nullable();         
            $table->integer('quantity_sold')->default(0);   
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
        Schema::dropIfExists('products');
    }
}
