<?php


test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
//hook
beforeEach(function () {
    $this->password = 'password';
    $this->email = fake()->unique()->safeEmail();
    $this->phone = fake()->unique()->numerify('09########');
    $this->name = fake()->name();
});

it('register', function () {

    $response = $this->postJson('/api/register', [
        'name' => $this->name,
        'email' => $this->email,
        'password' => $this->password,
        'password_confirmation' => $this->password,
        'phone' => $this->phone,
    ]);

    expect($response)->toMatchSnapshot();
    $response->assertStatus(200);
});


it('verify otp and create user', function () {
    $this->seed();

    $registerResponse = $this->postJson('/api/register', [
        'name' => $this->name,
        'email' => $this->email,
        'password' => $this->password,
        'password_confirmation' => $this->password,
        'phone' => $this->phone,
    ]);

    $registerResponse->assertStatus(200);

    $otp = \App\Models\Otp::where('email', $this->email)->first();
    expect($otp)->not->toBeNull();

    $verifyResponse = $this->postJson('/api/verify-otp', [
        'email' => $this->email,
        'otp_code' => $otp->otp_code,
    ]);

    $verifyResponse->assertStatus(200);
    $verifyResponse->assertJsonStructure([
        'message',
        'user' => [
            'id',
            'name',
            'email',
            'phone'
        ],
        'token'
    ]);

    $this->assertDatabaseHas('users', [
        'email' => $this->email
    ]);
});
