<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('countries')) {
            // Pais Iso3166
            Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('active')->default(false);
                $table->string('alpha2_code', 2);
                $table->string('alpha3_code', 3);
                $table->unsignedInteger('numeric_code');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('country_translations')) {
            Schema::create('country_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('country_id');
                $table->string('locale')->index();
                $table->string('name')->nullable();
                $table->string('short_name')->nullable();
                $table->softDeletes();

                $table->unique(['country_id','locale']);
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            });

        }

        if (!Schema::hasTable('ccaas')) {
            Schema::create('ccaas', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('active')->default(false);
                $table->unsignedInteger('country_id');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');

            });
        }

        if (!Schema::hasTable('ccaa_translations')) {
            Schema::create('ccaa_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('ccaa_id');
                $table->string('locale')->index();
                $table->string('name')->nullable();
                $table->softDeletes();

                $table->unique(['ccaa_id','locale']);
                $table->foreign('ccaa_id')->references('id')->on('ccaas')->onDelete('cascade');
            });

        }

        if (!Schema::hasTable('provinces')) {
            Schema::create('provinces', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('active')->default(false);
                $table->unsignedInteger('ccaa_id')->nullable();
                $table->unsignedInteger('country_id');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('ccaa_id', 'ccaa_fk_3824576765325df9ec9043363b06afd0-9149')->references('id')->on('ccaas');
                $table->foreign('country_id', 'country_fk_41aa4b811566240e4777e2e3b4810fed-7955')->references('id')->on('countries');
            });
        }

        if (!Schema::hasTable('province_translations')) {
            Schema::create('province_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('province_id')->unsigned();
                $table->string('locale')->index();
                $table->string('name');
                $table->softDeletes();

                $table->unique(['province_id', 'locale']);
                $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('active')->default(false);
                $table->unsignedInteger('ccaa_id')->nullable();
                $table->unsignedInteger('country_id');
                $table->unsignedInteger('province_id');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('country_id', 'country_fk_41aa4b811566240e4777e2e3b4810fed-1542')->references('id')->on('countries');
                $table->foreign('ccaa_id', 'ccaa_fk_3824576765325df9ec9043363b06afd0-1861')->references('id')->on('ccaas');
                $table->foreign('province_id', 'province_fk_1ec111cc9ca6eec6c8982c07485e6804-695')->references('id')->on('provinces');
            });
        }

        if (!Schema::hasTable('city_translations')) {
            Schema::create('city_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('city_id')->unsigned();
                $table->string('locale')->index();
                $table->string('name');

                $table->softDeletes();

                $table->unique(['city_id', 'locale']);
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
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
        Schema::dropIfExists('city_translations');
        Schema::dropIfExists('cities');

        Schema::dropIfExists('provinces_translations');
        Schema::dropIfExists('provinces');

        Schema::dropIfExists('ccaa_translations');
        Schema::dropIfExists('ccaas');

        Schema::dropIfExists('country_translations');
        Schema::dropIfExists('countries');
    }
}
