<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountPageTest extends TestCase
{
    public function test_account_page_renders_successfully(): void
    {
        $response = $this->get('/account');

        $response
            ->assertOk()
            ->assertSee('Кабинет читателя', false)
            ->assertSee('/api/v1/account/summary', false);
    }

    public function test_account_page_loads_real_loans_not_catalog(): void
    {
        $response = $this->get('/account');

        $response
            ->assertOk()
            ->assertSee('/api/v1/account/loans', false)
            ->assertDontSee('/api/v1/catalog-db?limit=6', false);
    }

    public function test_account_page_shows_loan_section(): void
    {
        $response = $this->get('/account');

        $response
            ->assertOk()
            ->assertSee('Мои книги')
            ->assertSee('Текущие выдачи из библиотечного фонда');
    }
}
