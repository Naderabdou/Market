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
        Schema::create('extras', function (Blueprint $table) {
            $table->id();
            $table->string('name_extra')->nullable();
            $table->double('price_extra')->nullable();
            $table->enum('status_extra', ['available', 'unavailable'])->default('available')->nullable();

          //  $table->string('img_extra')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->timestamp('updated_at')->useCurrent();

            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extras');
    }
};
