<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view payments');
    }

    /**
     * Determine whether the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        // Admin can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Director can view payments from their school
        if ($user->hasRole('director') && $user->director) {
            return $payment->payable->school_id === $user->director->school_id;
        }

        // Student can view their own payments
        if ($user->hasRole('student') && $user->student) {
            return $payment->payable_type === Student::class &&
                   $payment->payable_id === $user->student->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create payments.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create payments');
    }

    /**
     * Determine whether the user can update the payment.
     */
    public function update(User $user, Payment $payment): bool
    {
        // Admin can update any
        if ($user->hasRole('admin')) {
            return true;
        }

        // Director can update payments from their school
        if ($user->hasRole('director') && $user->director) {
            return $payment->payable->school_id === $user->director->school_id;
        }

        // 48-hour window for non-admin users
        if ($payment->paid_at && $payment->paid_at->diffInHours(now()) > 48) {
            return false;
        }

        return $this->view($user, $payment);
    }

    /**
     * Determine whether the user can delete the payment.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $this->update($user, $payment) && $payment->status === 'paid';
    }

    /**
     * Determine whether the user can restore the payment.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the payment.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->hasRole('admin');
    }
}