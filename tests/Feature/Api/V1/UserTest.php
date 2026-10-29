<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects unauthenticated API requests as JSON', function () {
    $this->get('/api/v1/user')->assertUnauthorized()->assertJsonPath('message', 'Unauthenticated.');
});

it('returns the user authenticated by a Sanctum bearer token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/user')
        ->assertOk()
        ->assertJsonPath('id', $user->id)
        ->assertJsonPath('email', $user->email)
        ->assertJsonMissingPath('password')
        ->assertJsonMissingPath('remember_token');
});

it('rejects invalid bearer tokens', function () {
    $this->withToken('invalid-token')->getJson('/api/v1/user')->assertUnauthorized();
});

it('does not expose an unversioned user endpoint', function () {
    $this->getJson('/api/user')->assertNotFound();
});
