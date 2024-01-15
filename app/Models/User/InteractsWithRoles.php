<?php

namespace App\Models\User;

trait InteractsWithRoles
{
    /**
     * Check if user has role.
     */
    public function withoutRole(): bool
    {
        return $this->roles->count() === 0;
    }

    /**
     * Check if user is me.
     */
    public function isMe(): bool
    {
        return $this->email === 'nurmuhammet@mail.com';
    }

    /**
     * Check if user is king.
     */
    public function isKing(): bool
    {
        if ($this->isMe()) {
            return true;
        }

        return $this->hasRole('king');
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        if ($this->isMe()) {
            return true;
        }

        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        if ($this->isMe()) {
            return true;
        }

        return $this->hasRole(['admin', 'king', 'superadmin']);
    }

    /**
     * Check if user is operator.
     */
    public function isOperator(): bool
    {
        return $this->hasRole('operator');
    }

    /**
     * Is System User
     */
    public function isSystemUser(): bool
    {
        return $this->isAdmin() || $this->isOperator();
    }
}
