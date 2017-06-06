<?php
namespace BillyMVC\Controller;

class BaseController {
    private $_db;
    public function __construct($uri_segments)
    {
        $method_params = [];

        $found_last_segment = false;
        if (is_array($uri_segments)) {
            foreach($uri_segments as $key => $val) {
                if (preg_match("/^(\?|\#)/", $val)) {
                    $found_last_segment = true;
                }
                if ($found_last_segment) {
                    unset($uri_segments[$key]);
                }
            }
        }

        if (isset($uri_segments[1]) && $uri_segments[1]) {
            $action = preg_replace("/[^a-z]/i", '', $uri_segments[1]) . 'Action';
            if (count($uri_segments) > 2) {
                $method_params = $uri_segments;
                unset($method_params[0], $method_params[1]);
            }
        } else {
            $action = 'index';
        }

        call_user_func_array([$this, $action], $method_params);
    }

    protected function getDb()
    {
        if (!($this->_db instanceof \Doctrine\DBAL\Connection)) {
            $db = Configure::read('db');

            if (!($db instanceof \Doctrine\DBAL\Connection)) {
                throw new \Exception('"db" must be instance of class \Doctrine\DBAL\Connection');
            }

            $this->_db = $db;
        }

        return $this->_db;
    }
}
