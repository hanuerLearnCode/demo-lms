<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfficeClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'faculty' => $this->faculty->name,
            'students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'user_id' => $student->user->id,
                    'name' => $student->user->name,
                    'email' => $student->user->email,
                ];
            }),
        ];
    }
}
