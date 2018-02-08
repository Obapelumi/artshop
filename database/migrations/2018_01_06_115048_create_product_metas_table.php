<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateProductMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unique();
            $table->dateTime('stock_updated_at')->default(Carbon::now());
            $table->string('size_type')->nullable();
            $table->string('sizes')->nullable();
            $table->float('length')->nullable();            
            $table->float('breadth')->nullable();           
            $table->float('height')->nullable();            
            $table->float('weight')->nullable();
            $table->longText('other_info')->nullable();
            $table->float('discount')->default(1); 
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
        Schema::dropIfExists('product_metas');
    }
}
