<?php

namespace Tests\Unit\Services;

use App\Services\Library\IsbnService;
use PHPUnit\Framework\TestCase;

class IsbnServiceTest extends TestCase
{
    private IsbnService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new IsbnService();
    }

    // ── normalize ─────────────────────────────────────────────────

    public function test_normalize_strips_hyphens_and_spaces(): void
    {
        $this->assertEquals('9783161484100', $this->service->normalize('978-3-16-148410-0'));
    }

    public function test_normalize_uppercases_lowercase_x(): void
    {
        $this->assertEquals('080442957X', $this->service->normalize('0-8044-2957-x'));
    }

    public function test_normalize_empty_string_returns_empty(): void
    {
        $this->assertEquals('', $this->service->normalize(''));
    }

    public function test_normalize_removes_all_non_isbn_chars(): void
    {
        $this->assertEquals('9780306406157', $this->service->normalize(' 978 0306406157 '));
    }

    // ── validate ─────────────────────────────────────────────────

    public function test_validate_valid_isbn13(): void
    {
        $result = $this->service->validate('978-3-16-148410-0');

        $this->assertTrue($result['valid']);
        $this->assertEquals('ISBN-13', $result['format']);
        $this->assertEquals('9783161484100', $result['isbn']);
        $this->assertNull($result['error']);
    }

    public function test_validate_invalid_isbn13_checksum(): void
    {
        $result = $this->service->validate('978-3-16-148410-9');

        $this->assertFalse($result['valid']);
        $this->assertEquals('ISBN-13', $result['format']);
        $this->assertStringContainsString('checksum', $result['error']);
    }

    public function test_validate_valid_isbn10(): void
    {
        $result = $this->service->validate('0-306-40615-2');

        $this->assertTrue($result['valid']);
        $this->assertEquals('ISBN-10', $result['format']);
        $this->assertEquals('0306406152', $result['isbn']);
        $this->assertNull($result['error']);
    }

    public function test_validate_valid_isbn10_with_x_check_digit(): void
    {
        $result = $this->service->validate('0-8044-2957-X');

        $this->assertTrue($result['valid']);
        $this->assertEquals('ISBN-10', $result['format']);
    }

    public function test_validate_invalid_isbn10_checksum(): void
    {
        $result = $this->service->validate('0-306-40615-3');

        $this->assertFalse($result['valid']);
        $this->assertEquals('ISBN-10', $result['format']);
        $this->assertNotNull($result['error']);
    }

    public function test_validate_wrong_length_returns_error(): void
    {
        $result = $this->service->validate('12345');

        $this->assertFalse($result['valid']);
        $this->assertNull($result['format']);
        $this->assertStringContainsString('length', $result['error']);
        $this->assertStringContainsString('5', $result['error']);
    }

    public function test_validate_empty_string_returns_length_error(): void
    {
        $result = $this->service->validate('');

        $this->assertFalse($result['valid']);
        $this->assertNull($result['format']);
        $this->assertStringContainsString('length', $result['error']);
        $this->assertStringContainsString('0', $result['error']);
    }

    public function test_validate_11_digit_string_is_invalid_length(): void
    {
        $result = $this->service->validate('12345678901');

        $this->assertFalse($result['valid']);
        $this->assertNull($result['format']);
        $this->assertStringContainsString('11', $result['error']);
    }

    public function test_validate_isbn13_with_letter_in_body_is_invalid(): void
    {
        // normalize() keeps X but strips other non-digit chars, so '978X161484100' stays
        // 13 chars long. validateIsbn13() requires all-numeric via preg_match('/^\d{13}$/'),
        // so the X in a non-check position causes checksum validation to fail.
        $result = $this->service->validate('978X161484100');

        $this->assertFalse($result['valid']);
    }

    // ── isbn10to13 ────────────────────────────────────────────────

    public function test_isbn10to13_converts_valid_isbn10(): void
    {
        $isbn13 = $this->service->isbn10to13('0-306-40615-2');

        $this->assertEquals('9780306406157', $isbn13);

        // The result must itself be a valid ISBN-13.
        $validation = $this->service->validate($isbn13);
        $this->assertTrue($validation['valid']);
    }

    public function test_isbn10to13_converts_isbn10_with_x_check_digit(): void
    {
        $isbn13 = $this->service->isbn10to13('0-8044-2957-X');

        $this->assertNotNull($isbn13);
        $this->assertEquals(13, strlen($isbn13));

        $validation = $this->service->validate($isbn13);
        $this->assertTrue($validation['valid']);
    }

    public function test_isbn10to13_returns_null_for_isbn13_input(): void
    {
        // ISBN-13 is 13 chars, not 10, so conversion must return null.
        $result = $this->service->isbn10to13('978-3-16-148410-0');

        $this->assertNull($result);
    }

    public function test_isbn10to13_returns_null_for_too_short_input(): void
    {
        $result = $this->service->isbn10to13('12345');

        $this->assertNull($result);
    }

    public function test_isbn10to13_returns_null_for_empty_input(): void
    {
        $result = $this->service->isbn10to13('');

        $this->assertNull($result);
    }

    public function test_isbn10to13_returns_null_for_11_char_input(): void
    {
        $result = $this->service->isbn10to13('12345678901');

        $this->assertNull($result);
    }
}
