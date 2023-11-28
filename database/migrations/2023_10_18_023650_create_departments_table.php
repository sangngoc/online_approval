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
        Schema::create('departments', function (Blueprint $table) {
            $table->string('dep_id', 10);
            $table->string('dep_name', 30);
            $table->string('unit_id', 10);
            
            $table->timestamps();

            $table->primary('dep_id');
            $table->foreign('unit_id')->references('unit_id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
