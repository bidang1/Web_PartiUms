<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SubEvent;
use App\Models\SubEventDocument;
use App\Http\Requests\GformLinkRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PartiSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_deactivated_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@parti.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->post('/auth', [
            'email' => 'inactive@parti.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors([
            'email' => 'Akun Anda telah dinonaktifkan.'
        ]);
    }

    public function test_active_session_is_terminated_upon_deactivation(): void
    {
        $user = User::factory()->create([
            'email' => 'kesekretariatan@parti.com',
            'password' => Hash::make('password123'),
            'role' => 'KESEKRETARIATAN',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Deactivate user in database
        $user->update(['is_active' => false]);

        // Attempt to access again
        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_document_download_prevents_idor_on_drafts(): void
    {
        $user = User::factory()->create();

        // 1. Create a draft sub-event
        $subEvent = SubEvent::create([
            'year' => 2026,
            'name' => 'Draft Competition',
            'slug' => 'draft-comp',
            'status' => 'DRAFT',
            'order' => 1,
            'type' => 'ONLINE',
        ]);

        // 2. Add a document
        $document = $subEvent->documents()->create([
            'label' => 'Guidebook Draft',
            'file_path' => 'documents/test.pdf',
            'file_type' => 'PDF',
            'file_size_bytes' => 1024,
            'order' => 1,
            'uploaded_by' => $user->id,
            'uploaded_at' => now(),
        ]);

        // 3. Attempt to download
        $response = $this->get(route('document.download', $document->id));
        $response->assertStatus(404);
    }

    public function test_gform_link_validation_rejects_phishing_attempts(): void
    {
        $rules = (new GformLinkRequest())->rules();

        // Valid link
        $validator1 = Validator::make(
            ['gform_links' => [['label' => 'Gelombang 1', 'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSfXYZ/viewform']]],
            $rules
        );
        $this->assertTrue($validator1->passes());

        // Valid forms.gle link
        $validator2 = Validator::make(
            ['gform_links' => [['label' => 'Pendaftaran Umum', 'url' => 'https://forms.gle/xyz123abc']]],
            $rules
        );
        $this->assertTrue($validator2->passes());

        // Phishing link with sub-string docs.google.com/forms
        $validator3 = Validator::make(
            ['gform_links' => [['label' => 'Phishing Form', 'url' => 'https://phishing-site.com/redirect?to=docs.google.com/forms/xyz']]],
            $rules
        );
        $this->assertFalse($validator3->passes());
    }

    public function test_sub_event_detail_page_renders_successfully(): void
    {
        $subEvent = SubEvent::create([
            'year' => 2026,
            'name' => 'Web Programming',
            'slug' => 'lomba-web-programming',
            'status' => 'PUBLISHED',
            'order' => 1,
            'type' => 'ONLINE',
            'gform_link' => [['label' => 'Daftar Lomba', 'url' => 'https://forms.gle/xyz123abc']],
        ]);

        $response = $this->get(route('sub-event.show', $subEvent->slug));
        $response->assertStatus(200);
    }
}
