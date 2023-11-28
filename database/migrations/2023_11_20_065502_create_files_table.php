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
        Schema::create('files', function (Blueprint $table) {
            $table->integerIncrements('f_id')->length(10);
            $table->string('file_name');
            $table->integer('req_id')->lenght(10)->lenght(10)->constrained(
                table: 'requests', indexName: 'req_id'
            );
            $table->string('seq_no')->lenght(10)->constrained(
                table: 'history__approve', indexName: 'seq_no'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
