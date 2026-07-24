<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->decimal('overall_score', 5, 2)->default(0.00);
            $table->decimal('economic_risk', 5, 2)->default(0.00);
            $table->decimal('weather_risk', 5, 2)->default(0.00);
            $table->decimal('geopolitical_risk', 5, 2)->default(0.00);
            $table->decimal('operational_risk', 5, 2)->default(0.00);
            $table->string('risk_category')->default('Low');
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
