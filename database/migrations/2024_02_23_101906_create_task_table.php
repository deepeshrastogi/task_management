<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('title',80);
            $table->text('content',80);
            $table->tinyInteger('status')->default(3)->comment('1-done | 2-in progress | 3-to do');
            $table->tinyInteger('is_published')->default(1)->comment('1-saved as draft | 2-published');
            $table->string('attachment')->nullable();
            $table->bigInteger('task_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('task_id')->references('id')->on('task');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
