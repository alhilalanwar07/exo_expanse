<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswakkriFormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_name_replaces_previous_data_and_writes_history(): void
    {
        $firstPayload = [
            'name' => 'Aqilah   Aqsha   Putri',
            'social_platform' => 'instagram',
            'social_account' => '@aqilah_first',
            'age' => 16,
        ];

        $this->postJson(route('social-form.store'), $firstPayload)
            ->assertOk()
            ->assertJson([
                'replaced_previous' => false,
            ]);

        $this->assertDatabaseCount('siswakkris', 1);
        $this->assertDatabaseHas('siswakkris', [
            'name' => 'AQILAH AQSHA PUTRI',
            'social_platform' => 'instagram',
            'social_account' => '@aqilah_first',
            'age' => 16,
        ]);

        $secondPayload = [
            'name' => 'aqilah aqsha putri',
            'social_platform' => 'tiktok',
            'social_account' => '@aqilah_updated',
            'age' => 17,
        ];

        $this->postJson(route('social-form.store'), $secondPayload)
            ->assertOk()
            ->assertJson([
                'replaced_previous' => true,
            ]);

        $this->assertDatabaseCount('siswakkris', 1);
        $this->assertDatabaseHas('siswakkris', [
            'name' => 'AQILAH AQSHA PUTRI',
            'social_platform' => 'tiktok',
            'social_account' => '@aqilah_updated',
            'age' => 17,
        ]);

        $this->assertDatabaseCount('siswakkri_histories', 2);
        $this->assertDatabaseHas('siswakkri_histories', [
            'name' => 'AQILAH AQSHA PUTRI',
            'social_platform' => 'instagram',
            'social_account' => '@aqilah_first',
            'age' => 16,
            'replaced_previous' => 0,
        ]);
        $this->assertDatabaseHas('siswakkri_histories', [
            'name' => 'AQILAH AQSHA PUTRI',
            'social_platform' => 'tiktok',
            'social_account' => '@aqilah_updated',
            'age' => 17,
            'replaced_previous' => 1,
        ]);
    }
}
