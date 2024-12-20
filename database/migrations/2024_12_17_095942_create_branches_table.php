<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key
            $table->string('branch_name');
            $table->string('branch_contact_number');
            $table->string('branch_address');
            $table->timestamps(); // Created at and Updated at fields
        });
    }

    public function down()
    {
        Schema::dropIfExists('branches');
    }
}

