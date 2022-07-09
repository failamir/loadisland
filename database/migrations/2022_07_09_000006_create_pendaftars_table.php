<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftarsTable extends Migration
{
    public function up()
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_tiket')->nullable();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('email');
            $table->string('no_hp');
            $table->string('checkin')->nullable();
            $table->string('payment')->nullable();
            $table->longText('notes')->nullable();
            $table->string('status_payment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
