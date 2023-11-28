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
        Schema::create('requests', function (Blueprint $table) {
            $table->integerIncrements('req_id')->length(10);
            $table->integer('route_id')->lenght(10)->constrained(
                table: 'request__routes', indexName: 'route_id'
            );
            $table->string('from_id')->lenght(10)->constrained(
                table: 'users', indexName: 'id'
            );
            $table->string('unit_id', 10)->nullable();
            $table->string('dep_id', 10)->nullable();
            $table->string('sec_id', 10)->nullable();
            $table->string('subject');
            $table->text('content');
            $table->string('state', 10)->default('0');

            $table->timestamps();

            // $table->foreign('route_id')->references('route_id')->on('request__routes');
            // $table->foreign('from_id')->references('id')->on('users');
            $table->foreign('unit_id')->references('unit_id')->on('units');
            $table->foreign('dep_id')->references('dep_id')->on('departments');
            $table->foreign('sec_id')->references('sec_id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
