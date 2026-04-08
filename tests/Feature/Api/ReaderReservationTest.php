<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReaderReservationTest extends TestCase
{
    private string $userId;
    private string $bookId;
    private ?string $copyId = null;
    private string $branchId;
    private array $sessionUser;
    private bool $pgsqlAvailable = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        if ($this->canUseLivePgsql()) {
            $this->pgsqlAvailable = true;
            Config::set('database.default', 'pgsql');
            DB::purge('pgsql');

            $this->branchId = DB::connection('pgsql')
                ->table('public.LibraryBranch')
                ->value('id');

            $this->userId = DB::connection('pgsql')
                ->table('public.User')
                ->value('id');

            $this->bookId = DB::connection('pgsql')
                ->table('public.Book')
                ->value('id');

            $this->copyId = DB::connection('pgsql')
                ->table('public.BookCopy')
                ->where('bookId', $this->bookId)
                ->where('status', 'AVAILABLE')
                ->value('id');

            if (! $this->userId || ! $this->bookId || ! $this->branchId) {
                $this->pgsqlAvailable = false;
            }

            $this->sessionUser = [
                'id' => $this->userId ?? '',
                'name' => 'Test Reader',
                'email' => 'test-reader@digital-library.test',
                'role' => 'student',
            ];
        }
    }

    protected function tearDown(): void
    {
        if ($this->pgsqlAvailable) {
            // Clean up test reservations.
            DB::connection('pgsql')
                ->table('public.Reservation')
                ->where('notes', 'like', '%reader_self_service%')
                ->whereRaw('"createdAt" > NOW() - INTERVAL \'5 minutes\'')
                ->delete();

            // Restore any copies we may have marked RESERVED back to AVAILABLE.
            if (! empty($this->copyId)) {
                DB::connection('pgsql')
                    ->table('public.BookCopy')
                    ->where('id', $this->copyId)
                    ->where('status', 'RESERVED')
                    ->update(['status' => 'AVAILABLE']);
            }
        }

        parent::tearDown();
    }

    private function canUseLivePgsql(): bool
    {
        try {
            DB::connection('pgsql')->getPdo();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function requireLivePgsql(): void
    {
        if (! $this->pgsqlAvailable) {
            $this->markTestSkipped('Live PostgreSQL with seed data not available.');
        }
    }

    private function authenticateSession(): self
    {
        return $this->withSession(['library.user' => $this->sessionUser]);
    }

    // --- Authentication tests ---

    public function test_create_reservation_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/account/reservations', [
            'bookId' => Str::uuid()->toString(),
        ]);

        $response->assertStatus(401);
    }

    public function test_cancel_reservation_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/account/reservations/' . Str::uuid()->toString() . '/cancel');

        $response->assertStatus(401);
    }

    public function test_check_reservation_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/account/reservations/check?bookId=' . Str::uuid()->toString());

        $response->assertStatus(401);
    }

    public function test_list_reservations_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/account/reservations');

        $response->assertStatus(401);
    }

    // --- Create reservation tests ---

    public function test_create_reservation_validates_input(): void
    {
        $this->requireLivePgsql();

        $response = $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', []);

        // Should return 422 because neither bookId nor isbn provided.
        $response->assertStatus(422);
    }

    public function test_create_reservation_rejects_nonexistent_book(): void
    {
        $this->requireLivePgsql();

        $response = $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', [
                'bookId' => Str::uuid()->toString(),
            ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'book_not_found']);
    }

    public function test_create_reservation_success_with_book_id(): void
    {
        $this->requireLivePgsql();

        $response = $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', [
                'bookId' => $this->bookId,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'created' => true,
            ])
            ->assertJsonStructure([
                'reservation' => ['id', 'status', 'reservedAt', 'expiresAt', 'book'],
            ]);

        $this->assertEquals('PENDING', $response->json('reservation.status'));
    }

    public function test_create_reservation_success_with_isbn(): void
    {
        $this->requireLivePgsql();

        $isbn = DB::connection('pgsql')
            ->table('public.Book')
            ->where('id', $this->bookId)
            ->value('isbn');

        if (! $isbn) {
            $this->markTestSkipped('No ISBN for test book.');
        }

        $response = $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', [
                'isbn' => $isbn,
            ]);

        // Could be 201 (created) or 409 (duplicate if previous test created one).
        $this->assertContains($response->status(), [201, 409]);

        if ($response->status() === 201) {
            $response->assertJson(['success' => true, 'created' => true]);
        }
    }

    public function test_create_reservation_prevents_duplicate(): void
    {
        $this->requireLivePgsql();

        // Ensure there's an active reservation first.
        $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', [
                'bookId' => $this->bookId,
            ]);

        // Second attempt should be blocked.
        $response = $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', [
                'bookId' => $this->bookId,
            ]);

        $response->assertStatus(409)
            ->assertJson(['error' => 'duplicate_reservation']);
    }

    // --- Check reservation tests ---

    public function test_check_reservation_returns_false_for_no_reservation(): void
    {
        $this->requireLivePgsql();

        // Use a book with no reservation.
        $otherBookId = DB::connection('pgsql')
            ->table('public.Book')
            ->where('id', '!=', $this->bookId)
            ->value('id');

        if (! $otherBookId) {
            $this->markTestSkipped('Need more than one book.');
        }

        $response = $this->authenticateSession()
            ->getJson('/api/v1/account/reservations/check?bookId=' . $otherBookId);

        $response->assertOk()
            ->assertJson(['hasActive' => false, 'reservation' => null]);
    }

    public function test_check_reservation_returns_true_when_active(): void
    {
        $this->requireLivePgsql();

        // Create a reservation first.
        $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', [
                'bookId' => $this->bookId,
            ]);

        $response = $this->authenticateSession()
            ->getJson('/api/v1/account/reservations/check?bookId=' . $this->bookId);

        $response->assertOk()
            ->assertJson(['hasActive' => true])
            ->assertJsonStructure(['reservation' => ['id', 'status']]);
    }

    public function test_check_reservation_with_isbn(): void
    {
        $this->requireLivePgsql();

        $isbn = DB::connection('pgsql')
            ->table('public.Book')
            ->where('id', $this->bookId)
            ->value('isbn');

        if (! $isbn) {
            $this->markTestSkipped('No ISBN for test book.');
        }

        $response = $this->authenticateSession()
            ->getJson('/api/v1/account/reservations/check?isbn=' . urlencode($isbn));

        $response->assertOk()
            ->assertJsonStructure(['hasActive', 'reservation']);
    }

    // --- Cancel reservation tests ---

    public function test_cancel_reservation_success(): void
    {
        $this->requireLivePgsql();

        // Create a reservation first.
        $createResponse = $this->authenticateSession()
            ->postJson('/api/v1/account/reservations', [
                'bookId' => $this->bookId,
            ]);

        $reservationId = $createResponse->json('reservation.id');
        if (! $reservationId) {
            // May already exist from prior test, find it.
            $reservationId = DB::connection('pgsql')
                ->table('public.Reservation')
                ->where('userId', $this->userId)
                ->where('bookId', $this->bookId)
                ->whereIn('status', ['PENDING', 'READY'])
                ->value('id');
        }

        $this->assertNotNull($reservationId, 'Need an active reservation to cancel.');

        $response = $this->authenticateSession()
            ->postJson("/api/v1/account/reservations/{$reservationId}/cancel");

        $response->assertOk()
            ->assertJson(['success' => true, 'cancelled' => true]);

        // Verify it's cancelled.
        $status = DB::connection('pgsql')
            ->table('public.Reservation')
            ->where('id', $reservationId)
            ->value('status');

        $this->assertEquals('CANCELLED', $status);
    }

    public function test_cancel_nonexistent_reservation(): void
    {
        $this->requireLivePgsql();

        $response = $this->authenticateSession()
            ->postJson('/api/v1/account/reservations/' . Str::uuid()->toString() . '/cancel');

        $response->assertStatus(404)
            ->assertJson(['error' => 'reservation_not_found']);
    }

    public function test_cancel_already_cancelled_reservation(): void
    {
        $this->requireLivePgsql();

        // Find a cancelled reservation.
        $cancelledId = DB::connection('pgsql')
            ->table('public.Reservation')
            ->where('userId', $this->userId)
            ->where('status', 'CANCELLED')
            ->value('id');

        if (! $cancelledId) {
            $this->markTestSkipped('No cancelled reservation for this user.');
        }

        $response = $this->authenticateSession()
            ->postJson("/api/v1/account/reservations/{$cancelledId}/cancel");

        $response->assertStatus(422)
            ->assertJson(['error' => 'cannot_cancel']);
    }

    // --- List reservations tests ---

    public function test_list_reservations_returns_data(): void
    {
        $this->requireLivePgsql();

        $response = $this->authenticateSession()
            ->getJson('/api/v1/account/reservations');

        $response->assertOk()
            ->assertJsonStructure([
                'authenticated',
                'data',
                'meta' => ['crmUserId', 'total'],
            ]);

        $this->assertIsArray($response->json('data'));
    }

    public function test_list_reservations_filters_by_status(): void
    {
        $this->requireLivePgsql();

        $response = $this->authenticateSession()
            ->getJson('/api/v1/account/reservations?status=PENDING');

        $response->assertOk();

        foreach ($response->json('data') as $reservation) {
            $this->assertEquals('PENDING', $reservation['status']);
        }
    }

    // --- Book page rendering ---

    public function test_book_page_shows_reserve_button_for_authenticated(): void
    {
        $this->requireLivePgsql();

        $isbn = DB::connection('pgsql')
            ->table('public.Book')
            ->where('id', $this->bookId)
            ->value('isbn');

        $response = $this->authenticateSession()
            ->get('/book/' . $isbn);

        $response->assertOk();
        $response->assertSee('id="reserve-btn"', false);
        $response->assertSee('handleReserve()', false);
    }

    public function test_book_page_shows_login_link_for_guest(): void
    {
        $isbn = '9786010001001';

        $response = $this->get('/book/' . $isbn);

        $response->assertOk();
        $response->assertSee('Войдите для бронирования');
        $response->assertDontSee('id="reserve-btn"', false);
    }

    // --- Account page rendering ---

    public function test_account_page_shows_reservations_section(): void
    {
        $this->requireLivePgsql();

        $response = $this->authenticateSession()
            ->get('/account');

        $response->assertOk();
        $response->assertSee('Мои бронирования');
        $response->assertSee('id="reservations-grid"', false);
        $response->assertSee('cancelReservation', false);
    }
}
