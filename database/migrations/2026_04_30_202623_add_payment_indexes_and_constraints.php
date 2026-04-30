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
        Schema::table('payments', function (Blueprint $table) {
            // Unique constraint to prevent duplicate payments
            $table->unique(['payable_type', 'payable_id', 'group_id', 'payment_month', 'status'], 'unique_payment_per_student_month')
                  ->whereNull('deleted_at'); // Only for active records

            // Performance indexes
            $table->index(['payable_type', 'payable_id', 'payment_month'], 'idx_payments_polymorphic_month');
            $table->index(['group_id'], 'idx_payments_group');
            $table->index(['paid_at'], 'idx_payments_paid_at');
            $table->index(['created_at'], 'idx_payments_created_at');
            $table->index(['status'], 'idx_payments_status');
        });

        // Indexes for transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['transactionable_type', 'transactionable_id'], 'idx_transactions_polymorphic');
            $table->index(['source_type', 'source_id'], 'idx_transactions_source');
            $table->index(['type'], 'idx_transactions_type');
            $table->index(['transaction_date'], 'idx_transactions_date');
        });

        // Indexes for charge_logs table
        Schema::table('charge_logs', function (Blueprint $table) {
            $table->index(['charge_month'], 'idx_charge_logs_month');
            $table->index(['created_at'], 'idx_charge_logs_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('unique_payment_per_student_month');
            $table->dropIndex('idx_payments_polymorphic_month');
            $table->dropIndex('idx_payments_group');
            $table->dropIndex('idx_payments_paid_at');
            $table->dropIndex('idx_payments_created_at');
            $table->dropIndex('idx_payments_status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_polymorphic');
            $table->dropIndex('idx_transactions_source');
            $table->dropIndex('idx_transactions_type');
            $table->dropIndex('idx_transactions_date');
        });

        Schema::table('charge_logs', function (Blueprint $table) {
            $table->dropIndex('idx_charge_logs_month');
            $table->dropIndex('idx_charge_logs_created_at');
        });
    }
};
