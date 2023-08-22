<?php

namespace App\Support\Data\Contracts;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

abstract class DataRules extends Data
{
    /**
     * The validation rules that apply to the request.
     */
    public static function rules(): array
    {
        return request()->isMethod(Request::METHOD_PUT)
            ? static::updateRules()
            : static::creationRules();
    }

    /**
     * The validation rules that apply when updating the resource.
     */
    abstract public static function updateRules(): array;

    /**
     * The validation rules that apply when creating the resource.
     */
    abstract public static function creationRules(): array;
}
