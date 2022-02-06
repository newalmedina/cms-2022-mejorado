<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('centers')) {
            Schema::create('centers', function (Blueprint $table) {
                $table->increments('id');

                $table->string('address');
$table->string('cp');
$table->string('city');
$table->string('contact');
$table->boolean('active')->default(0);
$table->string('name');
$table->unsignedInteger('province_id')->nullable();
$table->foreign('province_id','province_fk_1ec111cc9ca6eec6c8982c07485e6804-541')->references('id')->on('provinces');
$table->string('phone');
$table->string('email');


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
        
        Schema::dropIfExists('centers');
    }
}
