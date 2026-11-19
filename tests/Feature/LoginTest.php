<?php

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a seeded petugas can log in with their username and password', function () {
    $this->seed(UserSeeder::class);

    $this->post('/login', [
        'username' => 'petugas01',
        'password' => 'rahasia123',
    ])->assertRedirect('/dashboard/petugas');

    $this->assertAuthenticated();
});

test('a seeded admin can access the tariff page', function () {
    $this->seed(UserSeeder::class);

    $this->post('/login', [
        'username' => 'admin01',
        'password' => 'rahasia123',
    ])->assertRedirect('/dashboard/admin');

    $this->get('/dashboard/admin/tarif')->assertSuccessful();
});
