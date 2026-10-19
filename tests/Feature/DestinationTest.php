<?php

use App\Models\Destination;
use Database\Seeders\DestinationSeeder;

beforeEach(function () {
    $this->seed(DestinationSeeder::class);
});

test('there are exactly 13 tourism destinations and zero umkm destinations', function () {
    expect(Destination::count())->toBe(13);
    expect(Destination::where('category', 'Wisata')->count())->toBe(13);
    expect(Destination::where('category', 'UMKM')->count())->toBe(0);
});

test('all 13 tourism destinations from map are present with coordinates', function () {
    $expectedNames = [
        'Taman Tirta Asri',
        'Lembah Akasia',
        'Makam KH Kholib',
        'Princhsto',
        'Wisata Kuliner Nggruput',
        'Taman Kuda Pringsewu',
        'Taman Wisata BMJ',
        'Malliosewu',
        'Puncak Calliandra',
        'Wisata Ratu Banyu',
        'Lanakila Lake',
        'Desa Wisata Kampung Tapis Lugusari',
        'Taman Wisata Ndoro Putri',
    ];

    foreach ($expectedNames as $name) {
        $dest = Destination::where('name', $name)->first();
        expect($dest)->not->toBeNull();
        expect((float) $dest->latitude)->toBeLessThan(0);
        expect((float) $dest->longitude)->toBeGreaterThan(100);
    }
});

test('beranda page displays destinations', function () {
    $response = $this->get(route('beranda'));
    $response->assertStatus(200);
    $response->assertSee('Taman Tirta Asri');
    $response->assertSee('Lanakila Lake');
});

test('peta page loads and contains all destinations', function () {
    $response = $this->get(route('peta'));
    $response->assertStatus(200);
    $response->assertSee('Puncak Calliandra');
    $response->assertSee('Malliosewu');
});

test('daftar produk page displays destinations', function () {
    $response = $this->get(route('daftar-produk'));
    $response->assertStatus(200);
    $response->assertSee('Princhsto');
    $response->assertSee('Desa Wisata Kampung Tapis Lugusari');
});

test('detail page loads for destination', function () {
    $dest = Destination::first();
    $response = $this->get(route('detail', ['id' => $dest->id]));
    $response->assertStatus(200);
    $response->assertSee($dest->name);
});
