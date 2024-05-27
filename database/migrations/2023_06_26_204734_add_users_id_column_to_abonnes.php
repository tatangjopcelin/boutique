<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersIdColumnToAbonnees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abonnes', function (Blueprint $table) {
            $table->foreignId('users_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascasde');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abonnes', function (Blueprint $table) {
            Schema::dropIfExists('users_id');
        });
    }
}
