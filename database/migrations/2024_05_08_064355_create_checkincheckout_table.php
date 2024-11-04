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
        Schema::create('checkincheckout', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('room_type');
            $table->string('room_facility_type');
            $table->string('room_no');
          
            $table->string('checkin');
            $table->string('checkout');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('due_amount', 10, 2);
            
            $table->enum('status', ['CheckedIn', 'CheckedOut' , 'CheckedInANDCheckedOut'])->default('CheckedIn');
            $table->decimal('additional_payment', 10, 2)->default(0.00);
            $table->decimal('full_payment', 10, 2)->default(0.00);
            $table->decimal('full_payed_amount', 10, 2)->default(0.00);
            $table->text('note')->nullable();
            

            $table->foreign('booking_id')->references('id')->on('bookings');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkincheckout');
    }
};
