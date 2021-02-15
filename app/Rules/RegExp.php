<?php


namespace App\Rules;


use App\Validator;

class RegExp extends Validator
{
    public $message = 'Value does not equal expression';

    private $expression = '/.*/';

    public function __construct(string $expression, string $message = null): void
    {
        if (preg_match($expression, null) === false) {
            throw new \Exception('Invalid Regular expression supplied');
        }

        $this->expression = $expression;
        $this->message = $message ?? $this->message;
    }

    public function isValid($value): bool
    {
        return !preg_match($this->expression, $value) !== false;
    }
}