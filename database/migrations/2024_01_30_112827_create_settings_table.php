<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('currency')->nullable();
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('g:i A');
            $table->string('logo_light')->nullable();
            $table->string('logo_dark')->nullable();
            $table->string('title')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->string('invoice_prefix')->nullable();
            $table->string('bill_prefix')->nullable();
            $table->string('customer_prefix')->nullable();
            $table->string('supplier_prefix')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
