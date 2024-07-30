<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithSecretResource extends JsonResource
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
            'full_name' => $this->full_name,
            'nickname' => $this->nickname,
            'password' => $this->password,
            'birth' => $this->birth,
            'address' => $this->address,
            'shift' => $this->shift,
            'gender' => $this->gender,
            'profile_picture' => $this->profile_picture,
            'role' => $this->role,
        ];
    }
}
