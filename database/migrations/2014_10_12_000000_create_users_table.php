<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('password')->nullable();
            $table->integer('otp')->default('0');
            $table->text('reason')->nullable();
            $table->enum('status',['active','inactive','blocked','suspended'])->default('active');
            $table->enum('user_type',['0','1','2','3','4'])->default('4')->comment('0-customer,1-vendor,2-vendor&customer,3-admin,4-Not Assigned');
            $table->enum('verified',['1','0'])->default('0');
            $table->enum('details',['0','1','2'])->default('0');
            $table->enum('admin_approved',['1','0'])->default('0');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
