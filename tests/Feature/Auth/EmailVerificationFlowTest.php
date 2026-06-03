<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ActivateAccountNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_verification_notice_page(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/email/verify')
            ->assertOk();
    }

    public function test_user_can_resend_activation_email(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertRedirect();

        Notification::assertSentTo($user, ActivateAccountNotification::class);
    }

    public function test_signed_activation_link_marks_user_as_verified_and_redirects_to_success_page(): void
    {
        Event::fake([Verified::class]);
        $user = User::factory()->unverified()->create();

        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->get($signedUrl)
            ->assertRedirect(route('verification.success'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        Event::assertDispatched(Verified::class);
    }
}
