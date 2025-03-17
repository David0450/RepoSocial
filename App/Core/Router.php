<?php

class Router 
{

    private $_uri = [];
    private $_action = [];

    public function add($uri, $action = null) 
    {
        $this->_uri[] = '/' . trim($uri, '/');

        if ($action != null) 
        {
            $this->_action[] = $action;
        }
    }

    public function run() 
    {   
        if (isset($_POST['uri'])) 
        {
            $uriGet = '/' . $_POST['uri'];
        } 
        else 
        {
            $uriGet = isset($_GET['uri']) ? '/' . $_GET['uri'] : '/';
        }
        
        foreach ($this->_uri as $key => $value) 
        {
            if (preg_match("#^$value$#", $uriGet)) 
            {
                $action = $this->_action[$key];
                if(isset($_GET['parametro'])) {
                    $parametro = $_GET['parametro'];
                } else {
                    $parametro = null;
                }

                $this->runAction($action,$parametro);
            }
        }
    }

    private function runAction($action, $parametro) 
    {
        if($action instanceof \Closure)
        {
            $action();
        }  
        else 
        {
            if ($parametro == null) {
                $params = explode('@', $action);
                $obj = new $params[0];
                $obj->{$params[1]}();
            } else {
                $params = explode('@', $action);
                $obj = new $params[0];
                $obj->{$params[1]}($parametro);
            }
        }
    }

}