<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user): bool
    {
        if ($user->tokenCan(Abilities::DeleteTicket)) {
            return true;
        }
        return false;
    }

    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CreateTicket) ||
            $user->tokenCan(Abilities::CreateOwnTicket);
    }

    public function replace(User $user): bool
    {
        return $user->tokenCan(Abilities::ReplaceTicket);
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UpdateTicket)) {
            return true;
        } else if ($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id;
        }
        return false;
    }
}
