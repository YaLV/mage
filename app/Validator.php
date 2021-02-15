<?php


namespace App;


use App\Rules\NotEmpty;

class Validator
{
    public $message;
    public $errors = [];

    /**
     * @param array $rules
     */
    public function validate(array $rules)
    {
        $request = new Request();
        foreach ($rules as $item => $rules) {
            foreach($rules as $rule) {
                if (is_object($rule) && $rule instanceof $this && is_callable([$rule, 'isValid'])) {
                    if (!$rule->isValid($request->post($item))) {
                        $this->errors[$item][] = $rule->message;
                    }
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function validated()
    {
        return count($this->errors) == 0;
    }
}