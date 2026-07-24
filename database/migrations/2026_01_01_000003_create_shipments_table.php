<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            $table->foreignId('origin_country_id')->constrained('countries')->onDelete('cascade');
            $table->foreignId('destination_country_id')->constrained('countries')->onDelete('cascade');
            $table->foreignId('origin_port_id')->constrained('ports')->onDelete('cascade');
            $table->foreignId('destination_port_id')->constrained('ports')->onDelete('cascade');
            $table->string('status')->default('in_transit');
            $table->date('departure_date');
            $table->date('estimated_arrival');
            $table->date('actual_arrival')->nullable();
            $table->string('cargo_type');
            $table->string('risk_level')->default('Low');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
