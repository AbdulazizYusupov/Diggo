<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payable_type',
        'group_id',
        'payable_id',
        'user_id',
        'amount',
        'discount',
        'net_amount',
        'payment_type',
        'status',
        'payment_month',
        'is_installment',
        'installment_number',
        'total_installments',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'director_commission' => 'decimal:2',
        'is_installment' => 'boolean',
        'payment_month' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Polymorphic relationship (Student, Group, va h.k.)
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Kassir (to'lovni qabul qilgan user)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias for user (backward compatibility)
     */
    public function recordedBy(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Director (agar mavjud bo'lsa)
     */
    public function director(): BelongsTo
    {
        return $this->belongsTo(Director::class);
    }

    /**
     * Barcha transactionlar (payment, commission va h.k.)
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'source');
    }


    public function student(): BelongsTo
    {

        return $this->belongsTo(Student::class, 'payable_id');
    }

    /**
     * Direktor komissiya transactionlari
     */
    public function directorCommissions()
    {
        return $this->transactions()
            ->where('type', 'director_commission');
    }

    /**
     * O'qituvchi komissiya transactionlari
     */
    public function teacherCommissions()
    {
        return $this->transactions()
            ->where('type', 'teacher_commission');
    }

    /**
     * To'lov transactionlari
     */
    public function paymentTransactions()
    {
        return $this->transactions()
            ->where('type', 'payment');
    }

    /**
     * Scopes
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year);
    }

    public function scopeByPaymentType($query, string $type)
    {
        return $query->where('payment_type', $type);
    }
}
