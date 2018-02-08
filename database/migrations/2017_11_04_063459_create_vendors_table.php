<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unique();     // relationship with User model
            $table->integer('category_id'); // relationship with Category model
            $table->string('brand_name');   // vendor info (defaults to user name)
            $table->string('slug')->unique();           // vendor info
            $table->string('phone');                    // vendor info (admin)
            $table->longText('address');                // vendor info
            $table->integer('zip_code')->nullable();    // vendor info (admin)
            $table->longText('bio')->nullable();        // vendor  
            $table->string('image')->nullable();        // vendor info
            $table->string('acc_no');                   // vendor payment info
            $table->string('bank_code');                // vendor payment info
            $table->string('recipient_code')->nullable(); 
            $table->string('display')->nullable();  // vendor payment info --paystack
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
        Schema::dropIfExists('vendors');
    }
}
