<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_completo' => $this->nombre . ' ' . $this->apellido,
            'email' => $this->email,
            'fecha_registro' => $this->created_at->format('d/m/Y H:i'),
            'total_contactos' => $this->when($this->relationLoaded('contacts'), function () {
                return $this->contacts->count();
            }),
        ];
    }
}
