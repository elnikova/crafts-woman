<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Only admins can access admin panel.
     */
    public function accessAdminPanel(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Manage users (create, edit, delete).
     */
    public function manageUsers(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Manage categories.
     */
    public function manageCategories(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Manage subscriptions.
     */
    public function manageSubscriptions(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * View analytics.
     */
    public function viewAnalytics(User $user): bool
    {
        return $user->isAdmin();
    }
}

