<?php

namespace App\Policies;

use App\Models\User;

class BuyerPolicy
{
    /**
     * Buyer can access catalog.
     */
    public function viewCatalog(User $user): bool
    {
        return true;
    }

    /**
     * Buyer can purchase products.
     */
    public function purchaseProducts(User $user): bool
    {
        return true;
    }

    /**
     * Buyer can view product details.
     */
    public function viewProductDetails(User $user): bool
    {
        return true;
    }

    /**
     * Buyer can leave reviews.
     */
    public function leaveReviews(User $user): bool
    {
        return true;
    }

    /**
     * Buyer can communicate with sellers.
     */
    public function communicateWithSellers(User $user): bool
    {
        return true;
    }
}

