<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreToRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->bigInteger('student_id')->nullable();
            $table->string('role')->nullable();
            $table->integer('level')->nullable();
            $table->string('is_internal')->nullable();
            $table->boolean('is_work')->nullable();
            $table->boolean('still_working')->nullable();
            $table->string('enter_at')->nullable();
            $table->string('graduate_at')->nullable();
            $table->string('major')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn('student_id');
            $table->dropColumn('role');
            $table->dropColumn('level');
            $table->dropColumn('is_internal');
            $table->dropColumn('is_work');
            $table->dropColumn('still_working');
            $table->dropColumn('enter_at');
            $table->dropColumn('graduate_at');
            $table->dropColumn('major');
        });
    }
}
