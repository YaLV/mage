<?php


namespace App\Rules;


use App\Validator;

class IsEmail extends Validator
{
    public $message = 'Value is not email address';

    public function __construct(string $message = null)
    {
        $this->message = $message ?? $this->message;
    }

    public function isValid($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}