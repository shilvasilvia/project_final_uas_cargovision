<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('port_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('event_type');
            $table->string('severity')->default('Moderate');
            $table->text('description')->nullable();
            $table->date('alert_date');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_alerts');
    }
};
