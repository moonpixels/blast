<?php

namespace Database\Seeders;

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->has(
                Team::factory(3)
                    ->has(User::factory()->count(20), 'members')
                    ->hasInvitations(5)
                    ->hasLinks(50), 'ownedTeams'
            )
            ->has(
                Team::factory(2)
                    ->has(User::factory()->count(5), 'members')
                    ->hasInvitations(2)
                    ->hasLinks(10), 'teams'
            )
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ]);

        // Update Scout's search index.
        User::with('teams')->searchable();
    }
}
