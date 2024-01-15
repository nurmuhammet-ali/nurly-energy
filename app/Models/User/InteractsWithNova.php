<?php

namespace App\Models\User;

trait InteractsWithNova
{
    /**
     * Profile page of user
     */
    public function profilePage(): string
    {
        return sprintf('/resources/users/%s', $this->id);
    }
}
