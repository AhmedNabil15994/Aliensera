<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255);
            $table->text('layout')->nullable();
            $table->integer('is_active', 4)->nullable();
            $table->integer('is_main', 4)->nullable();
            $table->string('subject',255)->nullable();
            $table->string('type',50)->nullable();
            $table->string('source',255)->nullable();
            $table->string('email_to',255)->nullable();
            $table->integer('source_group', 11)->nullable();
            $table->integer('email_to_group', 11)->nullable();

            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();

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
        Schema::dropIfExists('email_templates');
    }
}
