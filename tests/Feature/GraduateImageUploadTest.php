<?php

use App\Models\Career;
use App\Models\Graduate;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('creates graduate with photo upload', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();

    Storage::fake('public');

    $photo = UploadedFile::fake()->image('graduate.jpg');

    $response = $this
        ->actingAs($user)
        ->post(route('graduates.store'), [
            'carnet' => 'G123456',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'gender' => 'male',
            'graduation_year' => 2024,
            'career_id' => $career->id,
            'photo' => $photo,
        ]);

    $response->assertRedirect(route('graduates.index'));

    $graduate = Graduate::latest()->first();
    expect($graduate->photo_path)->toBeTruthy();
    expect(Storage::disk('public')->exists($graduate->photo_path))->toBeTrue();
});

it('stores graduate photo path correctly in database', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();

    Storage::fake('public');

    $photo = UploadedFile::fake()->image('student.jpg');

    $this
        ->actingAs($user)
        ->post(route('graduates.store'), [
            'carnet' => 'G234567',
            'first_name' => 'María',
            'last_name' => 'González',
            'email' => 'maria@example.com',
            'phone' => '987654321',
            'gender' => 'female',
            'graduation_year' => 2023,
            'career_id' => $career->id,
            'photo' => $photo,
        ]);

    $graduate = Graduate::latest()->first();
    expect($graduate->photo_path)->toStartWith('photos/graduates/');
});

it('generates correct photo url attribute', function () {
    $graduate = Graduate::factory()->create([
        'photo_path' => 'photos/graduates/test.jpg',
    ]);

    expect($graduate->photo_url)->toBe('/storage/photos/graduates/test.jpg');
});

it('returns null photo url for graduate without photo', function () {
    $graduate = Graduate::factory()->create([
        'photo_path' => null,
    ]);

    expect($graduate->photo_url)->toBeNull();
});

it('updates graduate photo with new image', function () {
    $user = User::factory()->create();
    Storage::fake('public');
    $career = Career::factory()->create();
    $graduate = Graduate::factory()->create([
        'career_id' => $career->id,
        'photo_path' => 'photos/graduates/old.jpg',
    ]);

    Storage::disk('public')->put('photos/graduates/old.jpg', 'old content');

    $newPhoto = UploadedFile::fake()->image('new.jpg');

    $response = $this
        ->actingAs($user)
        ->put(route('graduates.update', $graduate), [
            'carnet' => $graduate->carnet,
            'first_name' => $graduate->first_name,
            'last_name' => $graduate->last_name,
            'email' => $graduate->email,
            'phone' => $graduate->phone,
            'gender' => $graduate->gender,
            'graduation_year' => $graduate->graduation_year,
            'career_id' => $career->id,
            'photo' => $newPhoto,
        ]);

    $response->assertRedirect();

    $graduate->refresh();
    expect($graduate->photo_path)->not()->toBe('photos/graduates/old.jpg');
    expect(Storage::disk('public')->exists('photos/graduates/old.jpg'))->toBeFalse();
    expect(Storage::disk('public')->exists($graduate->photo_path))->toBeTrue();
});

it('deletes graduate photo when graduate is deleted', function () {
    $user = User::factory()->create();
    Storage::fake('public');
    $graduate = Graduate::factory()->create([
        'photo_path' => 'photos/graduates/delete_me.jpg',
    ]);

    Storage::disk('public')->put('photos/graduates/delete_me.jpg', 'content');

    $this
        ->actingAs($user)
        ->delete(route('graduates.destroy', $graduate));

    expect(Storage::disk('public')->exists('photos/graduates/delete_me.jpg'))->toBeFalse();
});

it('creates graduate without photo', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();

    Storage::fake('public');

    $response = $this
        ->actingAs($user)
        ->post(route('graduates.store'), [
            'carnet' => 'G345678',
            'first_name' => 'Sin',
            'last_name' => 'Foto',
            'email' => 'nophoto@example.com',
            'phone' => '555666777',
            'gender' => 'male',
            'graduation_year' => 2024,
            'career_id' => $career->id,
        ]);

    $response->assertRedirect();

    $graduate = Graduate::latest()->first();
    expect($graduate->photo_path)->toBeNull();
    expect($graduate->photo_url)->toBeNull();
});

it('updates graduate without requiring photo', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();
    $graduate = Graduate::factory()->create([
        'career_id' => $career->id,
        'photo_path' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('graduates.update', $graduate), [
            'carnet' => $graduate->carnet,
            'first_name' => 'Updated',
            'last_name' => $graduate->last_name,
            'email' => 'updated@example.com',
            'phone' => $graduate->phone,
            'gender' => $graduate->gender,
            'graduation_year' => $graduate->graduation_year,
            'career_id' => $career->id,
        ]);

    $response->assertRedirect();

    $graduate->refresh();
    expect($graduate->photo_path)->toBeNull();
});
