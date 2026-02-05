<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transactionable_type',
        'transactionable_id',
        'source_type',
        'source_id',
        'amount',
        'balance_before',
        'balance_after',
        'type',
        'percentage',
        'base_amount',
        'description',
        'status',
        'approved_at',
        'paid_at',
        'created_by',
        'transaction_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'percentage' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Polymorphic: Kimga tegishli? (Student, Director, Teacher)
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Polymorphic: Qaysi manbadan? (Payment, LessonCharge, Commission)
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Transaction yaratgan user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Backward compatibility: student relationship
     */
    public function student()
    {
        return $this->transactionable()
            ->where('transactionable_type', Student::class);
    }

    /**
     * Scopes
     */
    public function scopePayments($query)
    {
        return $query->where('type', 'payment');
    }

    public function scopeCharges($query)
    {
        return $query->where('type', 'charge');
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', 'refund');
    }

    public function scopeDirectorCommissions($query)
    {
        return $query->where('type', 'director_commission');
    }

    public function scopeTeacherCommissions($query)
    {
        return $query->where('type', 'teacher_commission');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('transactionable_type', Student::class)
            ->where('transactionable_id', $studentId);
    }

    public function scopeForDirector($query, int $directorId)
    {
        return $query->where('transactionable_type', Director::class)
            ->where('transactionable_id', $directorId);
    }

    public function scopeForTeacher($query, int $teacherId)
    {
        return $query->where('transactionable_type', Teacher::class)
            ->where('transactionable_id', $teacherId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year);
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('transaction_date', [$from, $to]);
    }

    /**
     * Helpers
     */
    public function isCommission(): bool
    {
        return in_array($this->type, ['director_commission', 'teacher_commission']);
    }

    public function isPayment(): bool
    {
        return $this->type === 'payment';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function approve(): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function markAsPaid(): bool
    {
        return $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}