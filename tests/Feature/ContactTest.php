<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithToken()
    {
        $user = User::create([
            'nombre' => 'Test',
            'apellido' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    /**
     * Test authenticated user can create a contact
     */
    public function test_authenticated_user_can_create_contact(): void
    {
        $auth = $this->createUserWithToken();

        $contactData = [
            'nombre' => 'María',
            'apellido' => 'García',
            'telefono' => '+34 612 345 678',
            'email' => 'maria@example.com',
            'direccion' => 'Calle Mayor 123',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'nombre', 'apellido', 'telefono', 'email', 'direccion']
            ]);

        $this->assertDatabaseHas('contacts', [
            'user_id' => $auth['user']->id,
            'email' => 'maria@example.com',
            'telefono' => '+34 612 345 678'
        ]);
    }

    /**
     * Test user cannot create contact with duplicate phone for same user
     */
    public function test_user_cannot_create_contact_with_duplicate_phone(): void
    {
        $auth = $this->createUserWithToken();

        // Crear primer contacto
        Contact::create([
            'user_id' => $auth['user']->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '+34 612 345 678',
            'email' => 'juan@example.com',
        ]);

        // Intentar crear segundo contacto con mismo teléfono
        $contactData = [
            'nombre' => 'María',
            'apellido' => 'García',
            'telefono' => '+34 612 345 678',
            'email' => 'maria@example.com',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->postJson('/api/contacts', $contactData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telefono']);
    }

    /**
     * Test user cannot create contact with duplicate email for same user
     */
    public function test_user_cannot_create_contact_with_duplicate_email(): void
    {
        $auth = $this->createUserWithToken();

        // Crear primer contacto
        Contact::create([
            'user_id' => $auth['user']->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '+34 612 345 678',
            'email' => 'juan@example.com',
        ]);

        // Intentar crear segundo contacto con mismo email
        $contactData = [
            'nombre' => 'María',
            'apellido' => 'García',
            'telefono' => '+34 698 765 432',
            'email' => 'juan@example.com',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->postJson('/api/contacts', $contactData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test user can list their contacts
     */
    public function test_user_can_list_their_contacts(): void
    {
        $auth = $this->createUserWithToken();

        // Crear algunos contactos
        Contact::create([
            'user_id' => $auth['user']->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '+34 612 345 678',
            'email' => 'juan@example.com',
        ]);

        Contact::create([
            'user_id' => $auth['user']->id,
            'nombre' => 'María',
            'apellido' => 'García',
            'telefono' => '+34 698 765 432',
            'email' => 'maria@example.com',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->getJson('/api/contacts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'contacts' => [
                        '*' => ['id', 'nombre', 'apellido', 'telefono', 'email']
                    ],
                    'pagination' => [
                        'current_page',
                        'per_page',
                        'total',
                        'last_page'
                    ]
                ]
            ]);
    }

    /**
     * Test user can view a specific contact
     */
    public function test_user_can_view_specific_contact(): void
    {
        $auth = $this->createUserWithToken();

        $contact = Contact::create([
            'user_id' => $auth['user']->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '+34 612 345 678',
            'email' => 'juan@example.com',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'nombre', 'apellido', 'telefono', 'email']
            ]);
    }

    /**
     * Test user can update their contact
     */
    public function test_user_can_update_their_contact(): void
    {
        $auth = $this->createUserWithToken();

        $contact = Contact::create([
            'user_id' => $auth['user']->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '+34 612 345 678',
            'email' => 'juan@example.com',
        ]);

        $updateData = [
            'nombre' => 'Juan Carlos',
            'apellido' => 'Pérez García',
            'telefono' => '+34 612 345 678',
            'email' => 'juancarlos@example.com',
            'direccion' => 'Nueva dirección 123',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->putJson("/api/contacts/{$contact->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'nombre', 'apellido', 'telefono', 'email', 'direccion']
            ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'nombre' => 'Juan Carlos',
            'email' => 'juancarlos@example.com'
        ]);
    }

    /**
     * Test user can delete their contact
     */
    public function test_user_can_delete_their_contact(): void
    {
        $auth = $this->createUserWithToken();

        $contact = Contact::create([
            'user_id' => $auth['user']->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '+34 612 345 678',
            'email' => 'juan@example.com',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Contacto eliminado exitosamente'
            ]);

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id
        ]);
    }

    /**
     * Test user cannot access another user's contact
     */
    public function test_user_cannot_access_another_users_contact(): void
    {
        $auth1 = $this->createUserWithToken();

        $user2 = User::create([
            'nombre' => 'User2',
            'apellido' => 'Test',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
        ]);

        $contact = Contact::create([
            'user_id' => $user2->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '+34 612 345 678',
            'email' => 'juan@example.com',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth1['token'],
        ])->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(404);
    }
}
