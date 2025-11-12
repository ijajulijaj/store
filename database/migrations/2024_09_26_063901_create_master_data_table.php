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
        Schema::create('master_data', function (Blueprint $table) {
            $table->id();
            $table->string('outlet_code')->nullable();
            $table->string('location')->nullable();
            $table->string('mch_code')->nullable();
            $table->string('article_no')->nullable();
            $table->string('article_description')->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->string('uom')->nullable();
            $table->string('eanno')->nullable();
            $table->timestamps();

            // Add an index on the date field to improve search performance
            $table->index('outlet_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_data');
    }
};
