<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowNullTeacherIdInClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable(false)->change();
        });
    }
    
}
