<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
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
            'abbreviation' => $this->abbreviation,
            'office_classes' => $this->officeClasses->map(function ($officeClass) {
                return [
                    'id' => $officeClass->id,
                    'name' => $officeClass->name,
                ];
            }),
            'students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'user_id' => optional($student->user)->id,
                    'name' => optional($student->user)->name,
                    'email' => optional($student->user)->email,
                    'office_class' => optional($student->officeClass)->name,
                ];
            }),
        ];
    }
}
