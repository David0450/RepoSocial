<?php



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
            $obj = new $params[0];
            if ($parametro === null) {
                $obj->{$params[1]}();
            } else {
                $obj->{$params[1]}($parametro);
            }
        }
    }
}

/*public function run() {
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
*/