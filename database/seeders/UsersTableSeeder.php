<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'firstname' => 'Nurmuhammet',
                'lastname' => 'Allanov',
                'email' => 'nurmuhammet@mail.com',
                'password' => bcrypt('payload1010'),
            ],
        ];

        collect($users)->each(fn ($user) => User::create($user));
    }
}
