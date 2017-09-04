<?php


function __autoload($class_name){
    $fileExist = strtolower($class_name). ".php";
    if (file_exists($fileExist))
      include_once $fileExist;
}




//$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['QUERY_STRING'], '/'));
$className = ucwords($request[0]);
$controller = new $className();
$method = ucwords($request[1]);

$params = [];
if(sizeof($request)>2) {
  $params = array_slice($request, 2);
}


$_SERVER['REQUEST_METHOD']==="PUT" ? parse_str(file_get_contents("php://input", false , null, 0 , $_SERVER['CONTENT_LENGTH'] ), $_PUT): $_PUT=array();
if (count($request) == 0 || null == $controller) {
  throw new Exeption("Route not exist");
}

else {
<<<<<<< HEAD
  $controller->$method($id);
  file_put_contents("params.txt", $id);
=======
  $controller->$method($params);
>>>>>>> 33d58981ebd4035aed92e7b8f0db9b5bac9516c3
}
?>