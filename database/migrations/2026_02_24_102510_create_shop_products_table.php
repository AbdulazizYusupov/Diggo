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
        Schema::create('shop_products', function (Blueprint $table) {
           $table->id();
            $table->foreignId('shop_category_id')->constrained('shop_categories')->cascadeOnDelete();
            $table->string('name');                              // Mahsulot nomi
            $table->string('slug')->unique();                    // URL slug
            $table->text('description')->nullable();             // Tavsif
            $table->text('short_description')->nullable();       // Qisqacha tavsif
            $table->unsignedInteger('coin_price');               // Coin narxi
            $table->unsignedInteger('stock_quantity')->default(0); // Ombordagi miqdor
            $table->boolean('is_unlimited')->default(false);    // Cheksiz zaxirami
            $table->boolean('is_active')->default(true);        // Sotuvdami
            $table->boolean('is_featured')->default(false);     // Featured/top mahsulot
            $table->string('image')->nullable();                 // Asosiy rasm
            $table->json('images')->nullable();                  // Qo'shimcha rasmlar
            $table->string('sku')->nullable()->unique();         // Mahsulot kodi
            $table->string('brand')->nullable();                 // Brend
            $table->json('attributes')->nullable();              // Qo'shimcha xususiyatlar (rang, o'lcham...)
            $table->unsignedInteger('max_per_student')->default(1); // Bir student max nechta xarid qila oladi
            $table->integer('sort_order')->default(0);           // Tartib
            $table->unsignedBigInteger('total_sold')->default(0); // Jami sotilgan
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};
