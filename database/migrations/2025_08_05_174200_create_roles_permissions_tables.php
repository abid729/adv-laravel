<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesPermissionsTables extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'in_progress', 'completed']);
            $table->timestamps();
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('member');
            $table->primary(['user_id', 'team_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
}