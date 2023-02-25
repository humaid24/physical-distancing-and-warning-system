<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('isSupperAdmin', ['Y', 'N'])->default('N');
            $table->enum('isApprovedByAdmin', ['Y', 'N'])->default('N');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('isSupperAdmin');
            $table->dropColumn('isApprovedByAdmin');
        });
    }
}
