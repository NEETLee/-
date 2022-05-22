<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->engine = 'InnoDb';
            $table->id();
            $table->string('bill_no')->unique();
            $table->foreignId('mid')->references('id')->on('members');
            $table->foreignId('bid')->references('id')->on('books');
            $table->boolean('delay')->default(false);
            $table->float('money')->nullable();
            $table->dateTime('started_at');
            $table->date('ended_at')->nullable();
            $table->integer('duration')->default(30);
            $table->boolean('return')->default(false);
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
        Schema::dropIfExists('bills');
    }
}
