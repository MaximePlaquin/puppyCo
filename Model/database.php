<?php





class DbMySQL {
    public $mysqli;


    public function __construct() {
        $this->mysqli = new mysqli("localhost", "root", "root", "puppyco");
        if($this->mysqli->connect_errno) {
            die("Erreur lors de la connexion : " . $this->mysqli->connect_errno);
        }
    }



    public static function getConnection() {
        $sqli = new mysqli("localhost", "root", "root", "puppyco");
        if($sqli->connect_errno) {
            die("Erreur lors de la connexion : " . $sqli->connect_errno);
        }

        return $sqli;
    }

}







// $sqlite = DbFactory::create("sqlite", "CESI");
// $sqlite->insert("INSERT INTO UTILISATEUR (NOM, PRENOM) VALUES ('azzaz', 'azzaza')");
?>