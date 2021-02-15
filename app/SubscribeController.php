<?php


namespace App;


use App\DB\Subscriptions;
use App\Rules\Checked;
use App\Rules\IsEmail;
use App\Rules\NotEmpty;
use App\Rules\RegExp;

class SubscribeController
{

    public static function save(Request $request): void
    {
        $validator = new Validator();
        $validator->validate([
            'email' => [
                new NotEmpty('Email address is required'),
                new IsEmail('Please provide a valid e-mail address'),
                new RegExp('/.*\.cc$/', 'We are not accepting subscriptions from Colombia emails')
            ],
            'terms' => [
                new NotEmpty('You must accept the terms and conditions')
            ]
        ]);

        $_SESSION['errors'] = $validator->errors;
        $_SESSION['values'] = $request->post();

        if ($validator->validated()) {
            $subscription = new Subscriptions($request->post());
            $subscription->save();
            if (($subscription->getConnection()->errno ?? '') == '1062') {
                $_SESSION['errors']['email'][] = "This email has already been subscribed";
            } else {
                $_SESSION['subscribed'] = true;
            }
        }
        header('Location: /');
    }
}