<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateTablePaymentAcquirerAttempts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_acquirer_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->string('acquirer')->index();
            $table->string('external_reference')->index();
            $table->string('acquirer_code')->nullable();
            $table->string('acquirer_message')->nullable();
            $table->string('status')->index();

            $table->foreign('payment_id')
                ->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_acquirer_attempts');
    }
}
