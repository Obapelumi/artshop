<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('path')->unique();
            $table->integer('user_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('blogger_id')->nullable();
            $table->integer('blog_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->boolean('display')->default(true);
            $table->boolean('url')->default(false);
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
        Schema::dropIfExists('files');
    }
}
