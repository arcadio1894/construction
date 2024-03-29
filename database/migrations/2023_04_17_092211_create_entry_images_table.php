<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries');
            $table->text('code')->nullable();
            $table->text('image')->nullable();
            $table->enum('type', ['i', 'g', 'o']);
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
        Schema::dropIfExists('entry_images');
    }
}
