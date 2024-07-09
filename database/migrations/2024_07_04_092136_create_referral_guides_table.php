<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_guides', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_transfer')->nullable();
            $table->foreignId('reason_transfer_id')->nullable()->constrained('reason_transfers');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->mediumText('receiver')->nullable();
            $table->string('document')->nullable();
            $table->longText('arrival_point')->nullable();
            $table->string('placa')->nullable();
            $table->mediumText('driver')->nullable();
            $table->string('driver_licence')->nullable();
            $table->foreignId('shipping_manager_id')->nullable()->constrained('shipping_managers');
            $table->tinyInteger('enabled_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_guides');
    }
}
