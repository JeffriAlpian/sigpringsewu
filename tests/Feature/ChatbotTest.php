<?php

use Database\Seeders\DestinationSeeder;

beforeEach(function () {
    $this->seed(DestinationSeeder::class);
});

test('chatbot requires a message', function () {
    $response = $this->postJson(route('chatbot.message'), []);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['message']);
});

test('chatbot responds to greeting with assistant introduction', function () {
    $response = $this->postJson(route('chatbot.message'), [
        'message' => 'Halo Si Pringgo!',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['status', 'reply', 'suggestions']);
    $response->assertJsonPath('status', 'success');

    $reply = $response->json('reply');
    expect($reply)->toContain('Si Pringgo');
    expect($reply)->toContain('Pringsewu.com');
});

test('chatbot responds to tourism recommendations query', function () {
    $response = $this->postJson(route('chatbot.message'), [
        'message' => 'Rekomendasi tempat wisata alam terbaik di Pringsewu',
    ]);

    $response->assertStatus(200);
    $reply = $response->json('reply');
    expect($reply)->toContain('Rekomendasi Destinasi Wisata');
});

test('chatbot responds to specific destination query', function () {
    $response = $this->postJson(route('chatbot.message'), [
        'message' => 'Informasi tentang Lanakila Lake',
    ]);

    $response->assertStatus(200);
    $reply = $response->json('reply');
    expect($reply)->toContain('Lanakila Lake');
    expect($reply)->toContain('Rating');
});

test('chatbot responds to ticket price query', function () {
    $response = $this->postJson(route('chatbot.message'), [
        'message' => 'Berapa harga tiket masuk?',
    ]);

    $response->assertStatus(200);
    $reply = strtolower($response->json('reply'));
    expect($reply)->toContain('tiket');
});

test('chatbot responds to map query', function () {
    $response = $this->postJson(route('chatbot.message'), [
        'message' => 'Bagaimana cara melihat peta lokasi wisata?',
    ]);

    $response->assertStatus(200);
    $reply = strtolower($response->json('reply'));
    expect($reply)->toContain('peta');
});

test('chatbot responds to weather query', function () {
    $response = $this->postJson(route('chatbot.message'), [
        'message' => 'Bagaimana cuaca di Pringsewu hari ini?',
    ]);

    $response->assertStatus(200);
    $reply = $response->json('reply');
    expect($reply)->toContain('Cuaca');
    expect($reply)->toContain('Pringsewu');
});

test('chatbot responds to history and profile query', function () {
    $response = $this->postJson(route('chatbot.message'), [
        'message' => 'Ceritakan sejarah berdirinya Kabupaten Pringsewu',
    ]);

    $response->assertStatus(200);
    $reply = $response->json('reply');
    expect($reply)->toContain('Bambu Seribu');
    expect($reply)->toContain('Jejama Secancanan');
});

test('chatbot widget is present in public pages', function () {
    $response = $this->get(route('beranda'));
    $response->assertStatus(200);
    $response->assertSee('aiChatbot()', false);
    $response->assertSee('Si Pringgo', false);
});
