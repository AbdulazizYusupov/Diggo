<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Ommaviy to'ldirilishi mumkin bo'lgan atributlar.
     */
    protected $fillable = [
        'name',
        'type',
        'status',
        'group_id',
        'created_by',
        'description',
        'image',
        'file',
        'date',
        'start_time',
        'due_time',
        'max_coins',
        'is_required',
        'rank'
    ];

    /**
     * Atributlarni tegishli turlarga o'girish.
     */
    protected $casts = [
        'date' => 'date',
        'due_time' => 'datetime:H:i', // Vaqt formati uchun
        'max_coins' => 'integer',
        'is_required' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Guruh bilan bog'liqlik.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Faoliyatni yaratgan foydalanuvchi bilan bog'liqlik.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Tranzaksiyalar bilan bog'liqlik.
     */
    public function coinTransactions(): HasMany
    {
        return $this->hasMany(CoinTransaction::class);
    }
}