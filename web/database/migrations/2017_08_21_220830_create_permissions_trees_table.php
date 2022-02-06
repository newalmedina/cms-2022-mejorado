<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTreesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions_tree', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('permissions_id')->nullable();

            $table->nestedSet();

            $table->foreign('permissions_id')
                ->references('id')->on('permissions')
                ->onDelete('cascade');


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
        Schema::drop('permissions_tree');
    }
}
