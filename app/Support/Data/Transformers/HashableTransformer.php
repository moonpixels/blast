<?php

namespace App\Support\Data\Transformers;

use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class HashableTransformer implements Transformer
{
    /**
     * Transform the given value into a hash.
     */
    public function transform(DataProperty $property, mixed $value): mixed
    {
        return Hash::make($value);
    }
}
