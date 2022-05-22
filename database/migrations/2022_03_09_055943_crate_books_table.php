<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->engine = 'InnoDb';
            $table->id();
            $table->string('name');
            $table->string('author');
            $table->string('publisher');
            $table->string('category');
            $table->string('edition')->nullable();
            $table->string('ISBN')->unique();
            $table->float('price');
            $table->integer('num')->default(0);
            $table->integer('lend')->default(0);
            $table->string('location')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('books');
    }
}
