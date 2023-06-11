<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * Instantiate the resource.
     */
    public function __construct(mixed $resource, protected bool $withoutWrapping = false)
    {
        parent::__construct($resource);

        if ($this->withoutWrapping) {
            self::withoutWrapping();
        }
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'initials' => $this->initials,
        ];
    }
}
