<?php

namespace App\Actions\Links;

use App\Concerns\HasUrlInput;
use App\Models\Link;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteLink
{
    use AsAction, HasUrlInput;

    /**
     * Delete a link.
     */
    public function handle(Link $link): bool
    {
        return $link->delete();
    }
}
