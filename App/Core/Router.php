<?php

namespace App\Core;

class Router 
{
    private $_uri = [];
    private $_action = [];
    private $_methods = [];

    public function add($uri, $action = null, $method = 'GET') 
    {
        $this->_uri[] = '/' . trim($uri, '/');
        $this->_methods[] = strtoupper($method);

        if ($action != null) 
        {
            $this->_action[] = $action;
        }
    }

    public function run() 
    {   
        $uriGet = isset($_POST['uri']) ? '/' . $_POST['uri'] : (isset($_GET['uri']) ? '/' . $_GET['uri'] : '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Check if the URI contains an @ symbol
        if (preg_match("~@([^/]+)~", $uriGet, $matches)) {
            $username = $matches[1]; // Extract the username
            $uriGet = str_replace("@$username", "{username}", $uriGet); // Replace @username with 'user'

            $_GET['parametro'] = $username;
        }

        if (preg_match("~:([^/]+)~", $uriGet, $matches)) {
            $category = $matches[1];
            $uriGet = str_replace(":$category", "{category}", $uriGet);
            $_GET['category'] = $category;
        }

        if (preg_match("~;([^/]+)~", $uriGet, $matches)) {
            $tag = $matches[1];
            $uriGet = str_replace(";$tag", "{tag}", $uriGet);
            $_GET['tag'] = $tag;
        }

        if (preg_match("~/_([^/]+)~", $uriGet, $matches)) {
            $chatId = $matches[1];
            $uriGet = str_replace("_$chatId", "{chat_id}", $uriGet);
            $_GET['chat_id'] = $chatId;
        }




        foreach ($this->_uri as $key => $value) 
        {
            if (preg_match("#^$value$#", $uriGet) && $this->_methods[$key] === $requestMethod) 
            {
            $action = $this->_action[$key];
            $parametro = isset($_GET['parametro']) ? $_GET['parametro'] : null;
            $this->runAction($action, $parametro);
            }
        }
    }

    private function runAction($action, $parametro) 
    {
        if ($action instanceof \Closure)
        {
            $action();
        }  
        else 
        {
            $params = explode('@', $action);
            $className = 'App\\Controllers\\' . $params[0];
            if (!class_exists($className)) {
                throw new \Exception("Controller class $className not found.");
            }
            $obj = new $className;
            if ($parametro === null) {
                $obj->{$params[1]}();
            } else {
                $obj->{$params[1]}($parametro);
            }
        }
    }


    
}
?>