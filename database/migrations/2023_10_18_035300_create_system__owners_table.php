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
        Schema::create('system__owners', function (Blueprint $table) {
            $table->string('sys_id', 10);
            $table->string('sys_name')->nullable();
            $table->timestamps();   
            
            $table->primary('sys_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system__owners');
    }
};
