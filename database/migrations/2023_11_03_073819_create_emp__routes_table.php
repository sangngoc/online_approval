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
        Schema::create('emp__routes', function (Blueprint $table) {
            $table->integer('route_id')->lenght(10)->constrained(
                table: 'request__routes', indexName: 'route_id'
            );
            $table->string('emp_id')->lenght(10)->constrained(
                table: 'users', indexName: 'id'
            );
            $table->timestamps();

            $table->primary(['route_id','emp_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp__routes');
    }
};
