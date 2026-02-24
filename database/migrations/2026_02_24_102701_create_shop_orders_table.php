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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();            // Buyurtma raqami: ORD-2024-00001
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('shop_product_id')->constrained('shop_products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);    // Miqdor
            $table->unsignedInteger('coin_price');              // Xarid paytidagi narx (snapshot)
            $table->unsignedInteger('total_coins');             // Jami to'langan coin
            $table->enum('status', [
                'pending',    // Kutilmoqda
                'approved',   // Tasdiqlangan
                'delivered',  // Yetkazilgan
                'cancelled',  // Bekor qilingan
                'rejected',   // Rad etilgan
            ])->default('pending');
            $table->text('student_note')->nullable();           // Student izohi
            $table->text('admin_note')->nullable();             // Admin izohi
            $table->string('delivery_info')->nullable();        // Yetkazish ma'lumoti
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();      // Qachon ishlov berildi
            $table->timestamp('delivered_at')->nullable();      // Yetkazilgan vaqt
            $table->timestamps();
            $table->softDeletes();
            $table->index(['student_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};
