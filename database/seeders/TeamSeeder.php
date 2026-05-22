<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $manager1 = User::where('email', 'manager1@example.com')->first();
        $manager2 = User::where('email', 'manager2@example.com')->first();
        $users = User::where('role', 'user')->get();

        $teamA = Team::create([
            'team_name' => 'Campus Sprint Team',
            'created_by' => $manager1->id,
        ]);
        $teamA->users()->attach([$manager1->id, $users[0]->id, $users[1]->id]);

        $teamB = Team::create([
            'team_name' => 'Project Delivery Team',
            'created_by' => $manager2->id,
        ]);
        $teamB->users()->attach([$manager2->id, $users[2]->id, $users[3]->id, $users[4]->id]);
    }
}
