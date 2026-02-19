<?php

namespace App\Policies;

use App\Models\User;

class MasterPolicy
{
    /**
     * Master can access seller panel.
     */
    public function accessSellerPanel(User $user): bool
    {
        return $user->isMaster();
    }

    /**
     * Master can manage their own products.
     */
    public function manageProducts(User $user): bool
    {
        return $user->isMaster();
    }

    /**
     * Master can communicate with buyers.
     */
    public function communicateWithBuyers(User $user): bool
    {
        return $user->isMaster();
    }

    /**
     * Master can view feedback.
     */
    public function viewFeedback(User $user): bool
    {
        return $user->isMaster();
    }

    /**
     * Master can manage their own subscription.
     */
    public function manageOwnSubscription(User $user): bool
    {
        return $user->isMaster();
    }
}

