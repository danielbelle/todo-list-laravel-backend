<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

// Automatic groups based on folders
uses()->group('feature')->in('Feature');
uses()->group('unit')->in('Unit');

// Security groups based on subfolders
uses()->group('security')->in('Feature/Security');
uses()->group('rate-limiting')->in('Feature/Security');
uses()->group('cors')->in('Feature/Security');
uses()->group('sanitization')->in('Feature/Security');
uses()->group('error-handling')->in('Feature/Security');
uses()->group('pagination-security')->in('Feature/Security');

// Specific groups by test type
uses()->group('acceptance')->in('Feature/Acceptance');
uses()->group('concurrency')->in('Feature/Concurrency');
uses()->group('edge-cases')->in('Feature/EdgeCases');
uses()->group('end-to-end')->in('Feature/EndToEnd');
uses()->group('performance')->in('Feature/Performance');
uses()->group('requests')->in('Feature/Http/Requests');
uses()->group('resources')->in('Feature/Http/Resources');
uses()->group('smoke')->in('Feature/Smoke');

// Unit test groups
uses()->group('repositories')->in('Unit/Repositories');
uses()->group('services')->in('Unit/Services');

uses()->group('controllers')->in('Feature/Http/Controllers');

// Common configurations
uses(RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function authedUser(?User $user = null): \Illuminate\Foundation\Testing\TestCase
{
    if (! $user) {
        $user = User::query()->firstOrCreate([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
    }

    return \Pest\Laravel\actingAs($user);
}

function apiRoute(string $route): string
{
    return '/api/v1/' . $route;
}
