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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            //image
          //  $table->string('img_product');
            $table->double('price');
            $table->double('discount')->nullable()->default(0);
            //status
            $table->enum('status',['available','unavailable'])->nullable()->default('available');
            // foreign key constraint
            $table->foreignId('market_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
