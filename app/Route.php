<?php


namespace App;

Class Route
{
    const ROUTE_FILE = 'app/routes.php';

    /**
     * @var array
     */
    private $route;

    public function __construct() {
        include(self::ROUTE_FILE);
    }

    /**
     * @param $value
     * @param $action
     * @param $type
     * @return $this
     * @throws \Exception
     */
    private function addRoute($value, $action, $type) {
        if(is_callable($action)) {
            $this->route[$type][$value] = $action;
        }
        return $this;
    }

    /**
     * @param $value
     * @param $action
     * @throws \Exception
     */
    public function get($value, $action) {
        $this->addRoute($value, $action, 'get');
    }

    /**
     * @param $value
     * @param $action
     * @throws \Exception
     */
    public function post($value, $action) {
        $this->addRoute($value, $action, 'post');
    }

    /**
     * @param Request $request
     * @return mixed|null
     */
    public function getAction(Request $request) {
        $page = $request->get('section');

        return $this->route['post'][$page]??$this->route['get'][$page]??null;
    }
}