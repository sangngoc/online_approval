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
        Schema::create('history__approve', function (Blueprint $table) {
            $table->integer('req_id')->lenght(10)->lenght(10)->constrained(
                table: 'requests', indexName: 'req_id'
            );
            $table->string('emp_id')->lenght(10)->constrained(
                table: 'users', indexName: 'id'
            );
            $table->integer('seq_no')->lenght(10);
            $table->string('remark')->nullable();
            // $table->string('file')->nullable();
            $table->string('status', 30)->default('Create');
            

            $table->timestamps();

            $table->primary(['req_id','emp_id','seq_no']);
            // $table->foreign('req_id')->references('req_id')->on('requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history__approve');
    }
};
