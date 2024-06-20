<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'name' => $this->user->name,
            'email' => $this->user->email,
            'office_class' => $this->officeClass->name,
            'faculty' => $this->faculty->name,
            'courses' => $this->courses->map(function ($course) {
                return [
                    'course_id' => $course->id,
                    'name' => $course->name,
                    'abbreviation' => $course->abbreviation,
                    'credit' => $course->credit,
                ];
            }),
        ];
    }
}
