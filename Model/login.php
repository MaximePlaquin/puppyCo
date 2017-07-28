<?php 
include_once "database.php";
class login {

    public function __construct() {
    }

    // je n'arrive pas à faire le login
    public function POST($request, $post) {
        $this->mysqli = DbMySQL::getConnection();
        echo $post;
        $query = "SELECT * FROM USERS WHERE MAIL='".$post['mail']."' AND PASSWORD='".$post['password']."'";
        $result = $this->mysqli->select($query);
        if($result->fetch_array()==NULL) {
            die(header("HTTP/1.0 404 not found"));
        }
        else {
            session_start();
            $_SESSION['password'] = $_POST['login'];
            http_response_code(200);
        }

    }
}
?>