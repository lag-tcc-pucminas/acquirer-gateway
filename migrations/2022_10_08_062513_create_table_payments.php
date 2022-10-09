<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateTablePayments extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idempotency_id')->unique()->index();
            $table->string('status');
            $table->integer('installments');
            $table->string('acquirer')->index();
            $table->string('acquirer_reference')->nullable()->index();
            $table->string('nsu')->nullable();
            $table->string('authorization_code')->nullable();
            $table->string('mode');
            $table->integer('mcc')->index();
            $table->integer('value');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
