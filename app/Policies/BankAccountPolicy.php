<?php

namespace App\Policies;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankAccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bank accounts.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the bank account.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, BankAccount $bankAccount)
    {
        return $user->is_super_admin;
    }
}