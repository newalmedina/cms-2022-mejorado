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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('category_id')->nullable();
                $table->foreign('category_id')->references('id')->on('categories');
                $table->string('code')->unique();
                $table->string('name');

                $table->integer('amount');
                $table->decimal('price', 15, 2);
                $table->boolean('has_taxes')->default(0);
                $table->float('taxes', 15, 2)->nullable();
                $table->float('taxes_amount', 15, 2)->nullable();
                $table->decimal('real_price', 15, 2)->nullable();
                $table->text('description')->nullable();
                $table->boolean('active')->default(0);

                $table->timestamps();

                $table->softDeletes();
            });
        }
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
