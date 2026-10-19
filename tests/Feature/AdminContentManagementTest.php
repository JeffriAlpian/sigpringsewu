<?php

use App\Models\Berita;
use App\Models\Destination;
use App\Models\User;
use Database\Seeders\DestinationSeeder;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed([
        DestinationSeeder::class,
        UserSeeder::class,
    ]);
});

test('guests are redirected to login when accessing admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect('/login');
});

test('regular users cannot access admin dashboard and receive 403', function () {
    $user = User::where('role', 'user')->first();

    $response = $this->actingAs($user)->get(route('admin.dashboard'));
    $response->assertStatus(403);
});

test('admin can access admin dashboard and see summary statistics', function () {
    $admin = User::where('role', 'admin')->first();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Selamat Datang, '.$admin->name);
    $response->assertSee('Dasbor Ringkasan');
    $response->assertSee('Wisata & UMKM', false);
});

test('admin can view destinations list and create a new destination', function () {
    $admin = User::where('role', 'admin')->first();

    $response = $this->actingAs($admin)->get(route('admin.destinations.index'));
    $response->assertStatus(200);

    $createResponse = $this->actingAs($admin)->post(route('admin.destinations.store'), [
        'name' => 'Kopi Robusta Pagelaran',
        'category' => 'UMKM',
        'district' => 'Pagelaran',
        'location' => 'Desa Pagelaran',
        'status' => 'Buka',
        'rating' => 4.8,
        'review_count' => 15,
        'description' => 'Sentra pengolahan kopi robusta khas Pringsewu.',
        'weekday_price' => 15000,
        'weekend_price' => 15000,
        'open_hours' => '09:00 - 21:00 WIB',
        'latitude' => -5.352,
        'longitude' => 104.981,
        'tags' => 'Kopi, UMKM, Kuliner',
    ]);

    $createResponse->assertRedirect(route('admin.destinations.index'));
    $createResponse->assertSessionHas('success');

    $this->assertDatabaseHas('destinations', [
        'name' => 'Kopi Robusta Pagelaran',
        'category' => 'UMKM',
        'district' => 'Pagelaran',
    ]);
});

test('admin can update an existing destination', function () {
    $admin = User::where('role', 'admin')->first();
    $destination = Destination::first();

    $updateResponse = $this->actingAs($admin)->put(route('admin.destinations.update', $destination), [
        'name' => 'Nama Destinasi Terupdate',
        'category' => $destination->category,
        'district' => $destination->district,
        'location' => $destination->location,
        'status' => 'Buka',
        'rating' => 5.0,
        'review_count' => 100,
    ]);

    $updateResponse->assertRedirect(route('admin.destinations.index'));
    $updateResponse->assertSessionHas('success');

    expect($destination->fresh()->name)->toBe('Nama Destinasi Terupdate');
    expect((float) $destination->fresh()->rating)->toBe(5.0);
});

test('admin can delete a destination', function () {
    $admin = User::where('role', 'admin')->first();
    $destination = Destination::first();
    $id = $destination->id;

    $deleteResponse = $this->actingAs($admin)->delete(route('admin.destinations.destroy', $destination));
    $deleteResponse->assertRedirect(route('admin.destinations.index'));
    $deleteResponse->assertSessionHas('success');

    $this->assertDatabaseMissing('destinations', ['id' => $id]);
});

test('admin can create, update, and delete berita', function () {
    $admin = User::where('role', 'admin')->first();

    // Create
    $createResponse = $this->actingAs($admin)->post(route('admin.berita.store'), [
        'title' => 'Festival Bambu Pringsewu Resmi Dibuka',
        'slug' => 'festival-bambu-pringsewu-resmi-dibuka',
        'content' => 'Pemerintah Kabupaten Pringsewu resmi membuka acara tahunan Festival Bambu.',
        'published_at' => now()->toDateTimeString(),
    ]);

    $createResponse->assertRedirect(route('admin.berita.index'));
    $this->assertDatabaseHas('berita', [
        'slug' => 'festival-bambu-pringsewu-resmi-dibuka',
    ]);

    $berita = Berita::where('slug', 'festival-bambu-pringsewu-resmi-dibuka')->first();

    // Update
    $updateResponse = $this->actingAs($admin)->put(route('admin.berita.update', $berita), [
        'title' => 'Festival Bambu Pringsewu 2026 Sukses Besar',
        'content' => 'Acara ini dihadiri ribuan pengunjung dari berbagai wilayah.',
    ]);

    $updateResponse->assertRedirect(route('admin.berita.index'));
    expect($berita->fresh()->title)->toBe('Festival Bambu Pringsewu 2026 Sukses Besar');

    // Delete
    $deleteResponse = $this->actingAs($admin)->delete(route('admin.berita.destroy', $berita));
    $deleteResponse->assertRedirect(route('admin.berita.index'));
    $this->assertDatabaseMissing('berita', ['id' => $berita->id]);
});

test('admin can manage users and cannot delete themselves', function () {
    $admin = User::where('role', 'admin')->first();

    // Create a new user
    $createResponse = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Staf Dinas Baru',
        'email' => 'staf@pringsewu.com',
        'role' => 'admin',
        'password' => 'password123',
    ]);

    $createResponse->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['email' => 'staf@pringsewu.com']);

    $newUser = User::where('email', 'staf@pringsewu.com')->first();

    // Cannot delete self
    $selfDelete = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));
    $selfDelete->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);

    // Can delete other user
    $otherDelete = $this->actingAs($admin)->delete(route('admin.users.destroy', $newUser));
    $otherDelete->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
});
