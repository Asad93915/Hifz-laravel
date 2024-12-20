<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the role column with a default value of 'admin'
            $table->string('role')->default('admin');
            
            // Add the allow_to_create_* columns
            $table->boolean('allow_to_create_classes')->default(false);
            $table->boolean('allow_to_create_teachers')->default(false);
            $table->boolean('allow_to_create_students')->default(false);
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
            // Drop the columns added
            $table->dropColumn(['role', 'allow_to_create_classes', 'allow_to_create_teachers', 'allow_to_create_students']);
        });
    }
}
