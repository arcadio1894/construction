<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralGuideDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_guide_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_guide_id')->nullable()->constrained('referral_guides');
            $table->foreignId('quote_id')->nullable()->constrained('quotes');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->decimal('quantity', 9, 2)->default(0);
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
        Schema::dropIfExists('referral_guide_details');
    }
}
