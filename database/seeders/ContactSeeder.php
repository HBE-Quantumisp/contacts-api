<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario de prueba
        $user = User::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Crear contactos de prueba para el usuario
        $contacts = [
            [
                'nombre' => 'María',
                'apellido' => 'García',
                'telefono' => '+34 612 345 678',
                'email' => 'maria.garcia@example.com',
                'direccion' => 'Calle Mayor 123, Madrid, España',
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'López',
                'telefono' => '+34 698 765 432',
                'email' => 'carlos.lopez@example.com',
                'direccion' => 'Avenida de la Constitución 45, Barcelona, España',
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'telefono' => '+34 655 123 789',
                'email' => 'ana.martinez@example.com',
                'direccion' => 'Plaza del Sol 8, Valencia, España',
            ],
            [
                'nombre' => 'David',
                'apellido' => 'Rodríguez',
                'telefono' => '+34 687 456 123',
                'email' => 'david.rodriguez@example.com',
                'direccion' => 'Calle de Alcalá 200, Madrid, España',
            ],
            [
                'nombre' => 'Laura',
                'apellido' => 'Fernández',
                'telefono' => '+34 623 789 456',
                'email' => 'laura.fernandez@example.com',
                'direccion' => 'Paseo de Gracia 100, Barcelona, España',
            ],
        ];

        foreach ($contacts as $contactData) {
            $user->contacts()->create($contactData);
        }

        // Crear un segundo usuario
        $user2 = User::create([
            'nombre' => 'Carmen',
            'apellido' => 'Ruiz',
            'email' => 'carmen@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Crear algunos contactos para el segundo usuario
        $user2->contacts()->create([
            'nombre' => 'Pedro',
            'apellido' => 'Sánchez',
            'telefono' => '+34 666 111 222',
            'email' => 'pedro.sanchez@example.com',
            'direccion' => 'Calle Serrano 50, Madrid, España',
        ]);

        $user2->contacts()->create([
            'nombre' => 'Isabel',
            'apellido' => 'González',
            'telefono' => '+34 677 333 444',
            'email' => 'isabel.gonzalez@example.com',
            'direccion' => 'Rambla Catalunya 25, Barcelona, España',
        ]);
    }
}
