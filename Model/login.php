<?php 
include_once "database.php";
class login {
        public $msql;

    public function __construct() {
        $this->msql = DbFactory::create("mysql", "Cesi");
    }

    public function POST($request, $post) {
        $query = "SELECT * FROM tpfinal_user WHERE PSEUDO='".$post['pseudo']."' AND MDP='".md5($post['mdp'])."'";
        $result = $this->msql->select($query);
        if($result->fetch_array()==NULL) {
            die(header("HTTP/1.0 404 not found"));
        }
        else {
            session_start();
            $_SESSION['login'] = $_POST['pseudo'];
            http_response_code(200);
        }
    }
}
?>