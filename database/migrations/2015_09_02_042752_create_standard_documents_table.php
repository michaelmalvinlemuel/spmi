<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('standard_id')->unsigned();
            $table->foreign('standard_id')->references('id')->on('standards')->onDelete('cascade');
            $table->string('no')->unique();
            $table->date('date');
            $table->string('description')->unique();
            $table->string('document');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('standard_documents');
        array_map('unlink', glob(env('APP_UPLOAD') . "/standardDocument/*"));
    }
}