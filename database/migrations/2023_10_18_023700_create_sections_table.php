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
        Schema::create('sections', function (Blueprint $table) {
            $table->string('sec_id', 10);
            $table->string('sec_name', 30);
            $table->string('dep_id', 10);
            
            $table->timestamps();

            $table->primary('sec_id');
            $table->foreign('dep_id')->references('dep_id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
