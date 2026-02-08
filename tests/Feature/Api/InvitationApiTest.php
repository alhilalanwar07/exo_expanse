<?php

namespace Tests\Feature\Api;

use App\Enums\GuestStatus;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationApiTest extends TestCase
{
    use RefreshDatabase;

    private Invitation $invitation;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->invitation = Invitation::factory()->create(['user_id' => $user->id]);
    }

    public function test_can_submit_rsvp_as_confirmed(): void
    {
        $response = $this->postJson("/api/invitations/{$this->invitation->id}/rsvp", [
            'name' => 'John Doe',
            'status' => 'confirmed',
            'pax' => 2,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Konfirmasi kehadiran berhasil disimpan!',
            ]);

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $this->invitation->id,
            'name' => 'John Doe',
            'status' => GuestStatus::CONFIRMED->value,
            'pax' => 2,
        ]);
    }

    public function test_can_submit_rsvp_as_declined(): void
    {
        $response = $this->postJson("/api/invitations/{$this->invitation->id}/rsvp", [
            'name' => 'Jane Doe',
            'status' => 'declined',
            'pax' => 1,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $this->invitation->id,
            'name' => 'Jane Doe',
            'status' => GuestStatus::DECLINED->value,
            'pax' => 0,
        ]);
    }

    public function test_can_update_existing_rsvp(): void
    {
        Guest::factory()->create([
            'invitation_id' => $this->invitation->id,
            'name' => 'John Doe',
            'status' => GuestStatus::CONFIRMED,
            'pax' => 1,
        ]);

        $response = $this->postJson("/api/invitations/{$this->invitation->id}/rsvp", [
            'name' => 'John Doe',
            'status' => 'confirmed',
            'pax' => 3,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $this->invitation->id,
            'name' => 'John Doe',
            'pax' => 3,
        ]);

        $this->assertDatabaseCount('guests', 1);
    }

    public function test_rsvp_validation_fails_without_name(): void
    {
        $response = $this->postJson("/api/invitations/{$this->invitation->id}/rsvp", [
            'status' => 'confirmed',
            'pax' => 2,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_submit_wish(): void
    {
        $response = $this->postJson("/api/invitations/{$this->invitation->id}/wishes", [
            'name' => 'John Doe',
            'message' => 'Happy wedding! Wishing you both the best.',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Ucapan berhasil dikirim!',
            ]);

        $this->assertDatabaseHas('wishes', [
            'invitation_id' => $this->invitation->id,
            'name' => 'John Doe',
            'message' => 'Happy wedding! Wishing you both the best.',
        ]);
    }

    public function test_wish_validation_fails_without_message(): void
    {
        $response = $this->postJson("/api/invitations/{$this->invitation->id}/wishes", [
            'name' => 'John Doe',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['message']);
    }

    public function test_can_get_wishes_list(): void
    {
        Wish::factory()->count(5)->create([
            'invitation_id' => $this->invitation->id,
        ]);

        $response = $this->getJson("/api/invitations/{$this->invitation->id}/wishes");

        $response->assertOk()
            ->assertJsonCount(5, 'wishes')
            ->assertJsonStructure([
                'wishes' => [
                    '*' => ['id', 'name', 'message', 'initial', 'time'],
                ],
                'total',
            ]);
    }

    public function test_can_get_invitation_stats(): void
    {
        Wish::factory()->count(3)->create([
            'invitation_id' => $this->invitation->id,
        ]);

        Guest::factory()->count(2)->create([
            'invitation_id' => $this->invitation->id,
            'status' => GuestStatus::CONFIRMED,
            'pax' => 2,
        ]);

        Guest::factory()->create([
            'invitation_id' => $this->invitation->id,
            'status' => GuestStatus::DECLINED,
            'pax' => 0,
        ]);

        $response = $this->getJson("/api/invitations/{$this->invitation->id}/stats");

        $response->assertOk()
            ->assertJson([
                'total_wishes' => 3,
                'total_confirmed' => 4,
                'total_guests' => 3,
            ]);
    }
}
