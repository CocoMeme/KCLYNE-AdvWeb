<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['Actived', 'Deactivated'])->default('Actived');
            $table->string('address')->nullable();
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}

