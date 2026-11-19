<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminCleaningPageTest extends TestCase
{
    public function test_admin_cleaning_page_returns_success(): void
    {
        $admin = User::query()->where('email', 'admin@blockl.local')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.cleaning.index'))
            ->assertSuccessful();
    }

    public function test_login_page_returns_success(): void
    {
        $this->get(route('login'))->assertSuccessful();
    }
}