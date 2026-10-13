<?php

namespace App\Exceptions;

use RuntimeException;

class ScheduleException extends RuntimeException
{
    /** @var array<int,array{subject?:string,reason:string}> */
    protected array $conflicts = [];

    public function setConflicts(array $conflicts): static
    {
        $this->conflicts = $conflicts;
        return $this;
    }

    public function conflicts(): array
    {
        return $this->conflicts;
    }
}