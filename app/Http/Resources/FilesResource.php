<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'file_path' => $this->file_path,
            'file_size' => $this->file_size,
            'file_type' => $this->file_type,
            '_links' => [
//                '_self' => url('/api/dashboard/' . $this->id),
                '_self' => route('dashboard.file.show', $this->id),
            ],
            'children' => [
                'children' => $this->children,
            ],
        ];
//        return parent::toArray($request);
    }
}
