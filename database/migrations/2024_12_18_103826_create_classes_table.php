<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('class_short_description');
            $table->integer('total_strength');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['active', 'inactive', 'completed']);
            $table->json('active_days')->nullable();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
