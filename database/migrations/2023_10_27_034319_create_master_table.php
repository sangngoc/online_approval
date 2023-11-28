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
        Schema::create('master', function (Blueprint $table) {
            $table->string('sys_id',10);
            $table->string('emp_id')->lenght(10)->constrained(
                table: 'users', indexName: 'id'
            );

            $table->timestamps();

            $table->primary(['sys_id','emp_id']);
            $table->foreign('sys_id')->references('sys_id')->on('system__owners');
            // $table->foreign('emp_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master');
    }
};
