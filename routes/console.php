<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-user {email}', function (string $email) {
    $name = $this->ask('Nombre', 'Administrador');
    $password = $this->secret('Contraseña (mínimo 12 caracteres)');

    if (mb_strlen((string) $password) < 12) {
        $this->error('La contraseña debe tener al menos 12 caracteres.');
        return self::FAILURE;
    }

    User::updateOrCreate(
        ['email' => mb_strtolower($email)],
        ['name' => $name, 'password' => $password]
    );

    $this->info('Usuario creado o actualizado.');
    return self::SUCCESS;
})->purpose('Crear o actualizar un usuario con acceso a Brújula Brew');
