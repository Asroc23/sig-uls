<?php

use App\Models\Career;
use App\Models\Graduate;
use App\Models\User;

it('returns graduates by year', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();
    Graduate::factory()->create([
        'career_id' => $career->id,
        'graduation_year' => 2023,
    ]);
    Graduate::factory()->create([
        'career_id' => $career->id,
        'graduation_year' => 2023,
    ]);
    Graduate::factory()->create([
        'career_id' => $career->id,
        'graduation_year' => 2024,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard/data/by-year');

    $response->assertOk();
    $response->assertJsonStructure([
        'labels',
        'data',
    ]);

    $data = $response->json();
    expect($data['labels'])->toContain('2023', '2024');
    expect($data['data'])->toContain(2, 1);
});

it('returns graduates by gender', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();
    Graduate::factory()->create([
        'career_id' => $career->id,
        'gender' => 'male',
    ]);
    Graduate::factory()->create([
        'career_id' => $career->id,
        'gender' => 'male',
    ]);
    Graduate::factory()->create([
        'career_id' => $career->id,
        'gender' => 'female',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard/data/by-gender');

    $response->assertOk();
    $response->assertJsonStructure([
        'labels',
        'data',
    ]);

    $data = $response->json();
    expect($data['labels'])->toContain('Masculino', 'Femenino');
    expect($data['data'])->toContain(2, 1);
});

it('returns graduates by career', function () {
    $user = User::factory()->create();
    $career1 = Career::factory()->create(['name' => 'Carrera A']);
    $career2 = Career::factory()->create(['name' => 'Carrera B']);

    Graduate::factory()->create(['career_id' => $career1->id]);
    Graduate::factory()->create(['career_id' => $career1->id]);
    Graduate::factory()->create(['career_id' => $career2->id]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard/data/by-career');

    $response->assertOk();
    $response->assertJsonStructure([
        'labels',
        'data',
    ]);

    $data = $response->json();
    expect($data['labels'])->toContain('Carrera A', 'Carrera B');
    expect($data['data'])->toContain(2, 1);
});

it('filters graduates by year', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();

    Graduate::factory()->create([
        'career_id' => $career->id,
        'graduation_year' => 2023,
    ]);
    Graduate::factory()->create([
        'career_id' => $career->id,
        'graduation_year' => 2024,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard/data/by-year?graduation_year=2023');

    $response->assertOk();

    $data = $response->json();
    expect($data['labels'])->toContain('2023');
    expect(in_array(2023, $data['labels']))->toBeTrue();
});

it('filters graduates by gender', function () {
    $user = User::factory()->create();
    $career = Career::factory()->create();

    Graduate::factory()->create([
        'career_id' => $career->id,
        'gender' => 'male',
    ]);
    Graduate::factory()->create([
        'career_id' => $career->id,
        'gender' => 'female',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard/data/by-gender?gender=male');

    $response->assertOk();

    $data = $response->json();
    expect($data['labels'])->toContain('Masculino');
});

it('filters graduates by career', function () {
    $user = User::factory()->create();
    $career1 = Career::factory()->create();
    $career2 = Career::factory()->create();

    Graduate::factory()->create(['career_id' => $career1->id]);
    Graduate::factory()->create(['career_id' => $career2->id]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard/data/by-career?career_id=' . $career1->id);

    $response->assertOk();

    $data = $response->json();
    expect($data['labels'])->toContain($career1->name);
});

it('requires authentication to access statistics', function () {
    $response = $this->get('/dashboard/data/by-year');

    $response->assertRedirect(route('login'));
});
