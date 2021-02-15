<?php


namespace App;


class Request
{
    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->data = [
            'get' => $_GET,
            'post' => $_POST,
            'files' => $_FILES
        ];
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function get($key = null)
    {
        if(null == $key) {
            return $this->data['post']+$this->data['files']+$this->data['get'];
        }

        return $this->data['post'][$key]??$this->data['files'][$key]??$this->data['get'][$key]??null;
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function post($key = null) {
        if(null == $key) {
            return $this->data['post'];
        }

        return $this->data['post'][$key]??null;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return null != ($this->data['post'][$key]??$this->data['files'][$key]??$this->data['get'][$key]??null);
    }
}