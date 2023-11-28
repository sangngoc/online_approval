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
        Schema::create('request__routes', function (Blueprint $table) {
            $table->integerIncrements('route_id')->length(10);
            $table->string('route_name', 50)->default('Route Name');
            $table->string('type_id', 10);
            $table->string('emp_id')->lenght(10)->constrained(
                table: 'users', indexName: 'id'
            );
            $table->string('LV1')->length(10)->nullable();
            $table->string('LV2')->length(10)->nullable();
            $table->string('LV3')->length(10)->nullable();
            $table->string('LV4')->length(10)->nullable();
            $table->string('LV5')->length(10)->nullable();
            $table->string('LV6')->length(10)->nullable();
            $table->string('LV7')->length(10)->nullable();
            $table->string('LV8')->length(10)->nullable();
            $table->string('LV9')->length(10)->nullable();
            $table->string('LV10')->length(10)->nullable();

            $table->timestamps();

            $table->foreign('type_id')->references('type_id')->on('request__types');
            //$table->foreign('emp_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request__routes');
    }
};
