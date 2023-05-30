<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecettesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recettes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id') //or users_type
            ->constrained()
            ->onDelete('cascade');
            $table->string('nom');
            $table->string('description');
            $table->boolean('publie')->default(0);
            $table->string('lien')->unique();
            $table->foreignId('categories_id')->constrained()
            ->onDelete('cascade');
            $table->smallInteger('vote')->default(0);//le nombre de personne qui aiment la recette pour pouvoir distinguer les mieux votées aux moins votées
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
        Schema::dropIfExists('recettes');
    }
}
