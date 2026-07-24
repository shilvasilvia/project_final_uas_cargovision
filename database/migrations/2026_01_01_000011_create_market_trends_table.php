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
        Schema::create('market_trends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->string('currency_code', 10);
            $table->decimal('exchange_rate_usd', 12, 4);
            $table->decimal('inflation_rate', 5, 2);
            $table->decimal('gdp_growth_rate', 5, 2);
            $table->decimal('currency_impact_score', 5, 2)->default(50.0);
            $table->string('trend_direction', 20)->default('stable'); // bullish, bearish, stable
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_trends');
    }
};
