<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->decimal('gdp', 20, 2)->nullable();
            $table->decimal('inflation_rate', 8, 2)->nullable();
            $table->bigInteger('population')->nullable();
            $table->decimal('exports_usd', 20, 2)->nullable();
            $table->decimal('imports_usd', 20, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_data');
    }
};
