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








    //----------------------------------Methods-----------------------------------
    public function selectAll($table) {
        $query = "SELECT * FROM " . $table;
        echo $query."<br/>";
        return $this->mysqli->query($query);
    }


    public function insertUser($values) {
        $query = "INSERT INTO users (MAIL, PASSWORD, PSEUDO, MDP, LAST_MAJ) VALUES ('".$values["nom"]."', '".$values["prenom"]."', '".$values["pseudo"]."', '".md5($values["mdp"])."', STR_TO_DATE('".$date."', '%d %m %Y %T'))";
        echo $query."<br/>";
        $res = $this->mysqli->query($query);
        var_dump($this->mysqli->error);
    }



    public function insertDelivery() {
        $query = "INSERT INTO DELIVERY_INFOS (ADDRESS, TYPE_CB, NUM_CB, CRYPTO, POSTAL_CODE, CITY) VALUES ('', '', 0, 0, 0, '')";
        echo $query."<br/>";
        echo $this->mysqli->query($query);
        return $this->mysqli->lastInsertId();
    }

    public function update($query) {
        $res = $this->mysqli->query($query);
    }


    public function delete($query) {
        $res = $this->mysqli->query($query);
    }


    public function __destruct() {
        $this->mysqli->close();
    }
}







// $sqlite = DbFactory::create("sqlite", "CESI");
// $sqlite->insert("INSERT INTO UTILISATEUR (NOM, PRENOM) VALUES ('azzaz', 'azzaza')");
?>