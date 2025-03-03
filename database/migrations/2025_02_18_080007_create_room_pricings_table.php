<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('room_pricings', function (Blueprint $table) {
            $table->id();
            $table->string('currency');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('boarding_type_id')->constrained('bording_type')->onDelete('cascade');
            $table->foreignId('customer_type_id')->constrained('customer_types')->onDelete('cascade');
            $table->decimal('rate', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_pricings');
    }
};
