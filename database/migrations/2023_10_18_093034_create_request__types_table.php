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
        Schema::create('request__types', function (Blueprint $table) {
            $table->string('type_id', 10);
            $table->string('type_name');
            $table->string('sys_id', 10);
            $table->boolean('share')->default(0);
            
            $table->timestamps();

            $table->primary('type_id');
            $table->foreign('sys_id')->references('sys_id')->on('system__owners');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request__types');
    }
};
