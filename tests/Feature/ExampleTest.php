<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        // Only test the JSON endpoint — the Vue page needs the .vue file to exist
        $response = $this->getJson(route('site.home'));

        $response->assertOk();
    }
}