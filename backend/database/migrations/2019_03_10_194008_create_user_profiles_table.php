<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 255)->nullable();
            $table->string('license_no', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('home', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('company', 255)->nullable();
            $table->string('home_phone', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->text('region')->nullable();
            $table->text('location')->nullable();
            $table->string('postal_code', 255)->nullable();
            $table->text('about')->nullable();
            $table->text('info')->nullable();
            $table->string('tax_no', 55)->nullable();
            $table->string('secure_no', 55)->nullable();
            $table->text('address2')->nullable();
            $table->string('image', 55)->nullable();
            $table->integer('is_admin')->default(0);
            $table->integer('user_status_id');
            $table->string('status', 255)->default('None');
            $table->string('pin', 11)->nullable();
            $table->date('expire_date')->nullable();
            $table->integer('see_test')->default(0);
            $table->tinyInteger('is_driver_valid', 1)->default(0);
            $table->timestamp('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
            // Foriegn Keys
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('user_profiles');
    }
}
