<?php

namespace Database\Seeders;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if ($user) {
            // Check if invitation exists
            if (! Invitation::where('slug', 'romeo-juliet')->exists()) {
                // Create a sample invitation for the test user using factory
                Invitation::factory()->create([
                    'user_id' => $user->id,
                    'title' => 'The Wedding of Romeo & Juliet',
                    'groom_name' => 'Romeo Montague',
                    'groom_nickname' => 'Romeo',
                    'bride_name' => 'Juliet Capulet',
                    'bride_nickname' => 'Juliet',
                    'slug' => 'romeo-juliet',
                    'is_published' => true,
                ]);
            }
        }
    }
}
