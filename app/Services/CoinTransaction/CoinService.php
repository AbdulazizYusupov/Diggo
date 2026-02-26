<?php

namespace App\Services\CoinTransaction;

use App\Models\CoinTransaction;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * CoinService
 *
 * Single point of truth for every coin mutation in the system.
 * Every call that changes a student's coin balance MUST go through here so
 * that the coin_transactions ledger stays consistent with students.coins.
 *
 * All public methods assume they are already inside a DB::transaction() —
 * they do NOT open their own transaction. The caller is responsible for
 * wrapping multiple mutations in one atomic unit.
 */
class CoinService
{
    /**
     * Apply a signed coin delta to a student and write the ledger record.
     *
     * @param  Student     $student      The student whose balance changes.
     * @param  int         $delta        Positive = credit, negative = debit.
     * @param  string      $type         Must be a valid coin_transactions.type enum value.
     * @param  string      $description  Human-readable reason shown in the ledger.
     * @param  int|null    $activityId   Optional FK to activities table.
     * @return CoinTransaction           The persisted ledger record.
     *
     * @throws \InvalidArgumentException  When $delta is zero.
     * @throws \UnderflowException        When a debit would push coins below zero.
     */
    public function applyDelta(
        Student  $student,
        int      $delta,
        string   $type,
        string   $description,
        ?int     $activityId = null
    ): CoinTransaction {
        if ($delta === 0) {
            throw new \InvalidArgumentException(
                "CoinService: delta must not be zero for student #{$student->id}."
            );
        }

        // Re-read the current balance with a pessimistic lock so concurrent
        // requests cannot produce duplicate or incorrect balance_after values.
        $current = (int) DB::table('students')
            ->where('id', $student->id)
            ->lockForUpdate()
            ->value('coins');

        $newBalance = $current + $delta;

        if ($newBalance < 0) {
            throw new \UnderflowException(
                "CoinService: student #{$student->id} would have a negative balance ({$newBalance})."
            );
        }

        // Update the denormalised balance on the students row.
        DB::table('students')
            ->where('id', $student->id)
            ->update(['coins' => $newBalance]);

        // Refresh the in-memory model so callers see the new value immediately.
        $student->coins = $newBalance;

        // Write the immutable ledger entry.
        return CoinTransaction::create([
            'student_id'       => $student->id,
            'user_id'          => Auth::id(),
            'activity_id'      => $activityId,
            'coins'            => $delta,          // signed — positive or negative
            'type'             => $type,
            'description'      => $description,
            'balance_after'    => $newBalance,
            'transaction_date' => now()->toDateString(),
        ]);
    }

    /**
     * Convenience wrapper: credit coins (positive delta).
     */
    public function credit(
        Student $student,
        int     $amount,
        string  $type,
        string  $description,
        ?int    $activityId = null
    ): CoinTransaction {
        return $this->applyDelta($student, abs($amount), $type, $description, $activityId);
    }

    /**
     * Convenience wrapper: debit coins (negative delta).
     * Throws UnderflowException if the student does not have enough coins.
     */
    public function debit(
        Student $student,
        int     $amount,
        string  $type,
        string  $description,
        ?int    $activityId = null
    ): CoinTransaction {
        return $this->applyDelta($student, -abs($amount), $type, $description, $activityId);
    }
}