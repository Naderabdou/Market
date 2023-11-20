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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('password');
            $table->date('date_of_birth')->nullable();//only driver
            $table->string('vehicle_number')->unique()->nullable();//only driver
            $table->string('address')->nullable();// driver and market
            $table->date('registration_date')->nullable(); // market and driver
            $table->date('expiry_date')->nullable(); // market and driver
            $table->bigInteger('account_number')->unique()->nullable(); // market and driver
            $table->string('account_name')->unique()->nullable(); // market and driver
          //  $table->string('valid_until')->nullable(); // market and driver
            $table->string('api_domain')->nullable(); // market and driver
         //   $table->string('name_bank')->nullable(); // market
           // $table->integer('number_account')->unique()->nullable(); // market
            $table->string('name_branch')->nullable(); // market
         //   $table->string('cvv')->nullable(); // market and driver
            $table->string('subscription_value')->nullable(); // market and driver
            $table->string('order_value')->nullable(); // driver
            $table->enum('type', ['admin', 'user', 'driver','market'])->nullable();
            $table->enum('status',['block','unblock'])->nullable()->default('unblock');
            $table->enum('market',['open','close'])->nullable()->default('close');
            $table->bigInteger('commercial_registration_number')->unique()->nullable(); // market
            $table->string('img_profile')->nullable(); // market and driver
            $table->string('img_background')->nullable(); // market
            //foreign key constraint
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');




            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
