<?php


function __autoload($class_name){
    $fileExist = strtolower($class_name). ".php";
    if (file_exists($fileExist))
      include_once $fileExist;
}




$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['QUERY_STRING'], '/'));
$className = ucwords($request[0]);
$controller = new $className();

//var_dump($controller);

$_SERVER['REQUEST_METHOD']==="PUT" ? parse_str(file_get_contents("php://input", false , null, 0 , $_SERVER['CONTENT_LENGTH'] ), $_PUT): $_PUT=array();
if (count($request) ==0 || null == $controller) {
  throw new Exeption("Route not exist");
} else {
  $controller->$method($request, $_POST, $_PUT);
}
?>