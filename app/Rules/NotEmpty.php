<?php

namespace App\Rules;

use App\Validator;

class NotEmpty extends Validator
{
    public $message = 'Value should not be empty';

    public function __construct(string $message = null): void
    {
        $this->message = $message ?? $this->message;
    }

    public function isValid($value): bool
    {
        return ($value ?? false) && !empty(trim($value));
    }
}