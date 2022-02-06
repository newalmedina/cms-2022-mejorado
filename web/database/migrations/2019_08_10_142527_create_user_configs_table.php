<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->boolean('sidebar')->default(true);
            $table->string('skin')->default('{ "skin" : "skin-red-light", "data": ["light","navbar-navy","bg-navy","sidebar-light-purple","sidebar-light-navy"]}');
            $table->string('dashboard')->default('');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_configs', function(Blueprint $table)
        {
            $table->dropForeign('user_configs_user_id_foreign');
        });

        Schema::dropIfExists('user_configs');

    }
}
