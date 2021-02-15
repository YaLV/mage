<?php

namespace App;

class Controller
{
    const TEMPLATE = "layout.php";

    /**
     * @param Route $route
     * @param Request $request
     * @return mixed
     */
    public static function init(Route $route, Request $request) {
        $action = $route->getAction($request);
        if(null == $action) {
            $subscribed = $_SESSION['subscribed']??false;
            $errors = $_SESSION['errors']??[];
            $values = $_SESSION['values']??[];
            unset($_SESSION['subscribed'], $_SESSION['errors'], $_SESSION['values']);
            return include(implode(DIRECTORY_SEPARATOR, ['app', 'templates', self::TEMPLATE]));
        }

        return call_user_func($action, $request);
    }
}