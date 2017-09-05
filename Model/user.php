<?php

include_once "database.php";


class User {
    public $user_id;
    public $mail;
    public $mdp;
    public $cart_id;
    public $delivery_id;
    public $status;
    public $address;
    public $type_cb;
    public $num_cb;
    public $crypto;
    public $postal_code;
    public $city;
    
    
    public $mysqli;



    public function __construct() {
    }

    

    //-------------------------------Create---------------------------------
    public function create($data) {
        $this->mysqli = DbMySQL::getConnection();
        $this->mail = $data[0];
        $this->mdp = $data[1];
        $this->status = 'client';

        $query = "INSERT INTO DELIVERY_INFOS (ADDRESS, TYPE_CB, NUM_CB, CRYPTO, POSTAL_CODE, CITY) VALUES ('', '', 0, 0, 0, '')";
        //echo $query."<br/>";
        $result = $this->mysqli->query($query);        
        //echo $result;
        $this->delivery_id = $this->mysqli->insert_id;

        $query = "INSERT INTO CARTS (TOTAL_PRICE, DATE_VALIDATION, DELIVERY_INFO) VALUES (0, NULL, ".$this->delivery_id.")";
        //echo $query."<br/>";
        $result =  $this->mysqli->query($query);
        //echo $this->mysqli->error;
        $this->cart_id = $this->mysqli->insert_id;

        $query = "INSERT INTO USERS (MAIL, PASSWORD, CART_ID, DELIVERY_INFO, STATUS) VALUES ('".$this->mail."', '".hash('sha256', $this->mdp)."', ".$this->cart_id.", ".$this->delivery_id.", '".$this->status."')";
        $result =  $this->mysqli->query($query);
        $this->user_id = $this->mysqli->insert_id;

        $this->mysqli->commit();
        $this->mysqli->close();
    }



    //-------------------------------Read---------------------------------
    public function read($data) {
        if(is_array($data)) {
            if(count($data) < 1) {
                $id = null;
            }
            else {
                $id = $data[0];
            }
        }
        else {
            $id = $data;
        }
        $mArray = array();
        $this->mysqli = DbMySQL::getConnection();

        if($id!=null && $id>0) {
            $this->user_id = $id;
            $query = "SELECT * FROM USERS INNER JOIN DELIVERY_INFOS ON USERS.DELIVERY_INFO=DELIVERY_INFOS.ID WHERE USERS.ID = " .  $this->user_id;
            $result = $this->mysqli->query($query);  
            while($row = $result->fetch_array()) {
                $mArray[] = $row;
            }
            $this->mail = $mArray[0][1];
            $this->mdp = $mArray[0][2];
            $this->cart_id = $mArray[0][3];
            $this->delivery_id = $mArray[0][4];
            $this->status = $mArray[0][5];
            $this->address = $mArray[0][7];
            $this->type_cb = $mArray[0][8];
            $this->num_cb = $mArray[0][9];
            $this->crypto = $mArray[0][10];
            $this->postal_code = $mArray[0][11];
            $this->city = $mArray[0][12];
            $json = json_encode($mArray);
        }

        else {
            $query = "SELECT * FROM USERS INNER JOIN DELIVERY_INFOS ON USERS.DELIVERY_INFO=DELIVERY_INFOS.ID";
            $result = $this->mysqli->query($query);  
            
            while($row = $result->fetch_array()) {
                $mArray[] = $row;
            }
            $json = json_encode($mArray);
        }


        $this->mysqli->commit();
        $this->mysqli->close();
        echo $json;
        return $json;
    }




    //-------------------------------Update---------------------------------
    public function update($data) {
        $this->read(array_slice($data, 0, 1));

        $this->mysqli = DbMySQL::getConnection();

        $query = "UPDATE USERS SET MAIL='".$data[1]."', STATUS='".$data[2]."' WHERE ID=".$this->user_id;

        $result = $this->mysqli->query($query);
        //echo $query;
        //file_put_contents("request.txt", $query, FILE_APPEND);

        $query = "UPDATE DELIVERY_INFOS SET ADDRESS='".str_replace("%20"," ",$data[3])."', TYPE_CB='".$data[4]."', NUM_CB=".$data[5].", CRYPTO=".$data[6].", POSTAL_CODE=".$data[7].", CITY='".$data[8]."' WHERE ID=".$this->delivery_id;
        $result = $this->mysqli->query($query);
        //file_put_contents("request.txt", $query, FILE_APPEND);  
        
        $this->mysqli->close();
        return $this->read($this->user_id);
    }




    //-------------------------------Delete---------------------------------
    public function delete($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "DELETE FROM USERS WHERE ID=".$data[0];
        $result = $this->mysqli->query($query);
        
        //echo $query;
        //echo $this->mysqli->error;

        $this->mysqli->close();
    }




    //-------------------------------Connect---------------------------------
    public function connect($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "SELECT ID FROM USERS WHERE MAIL='".$data[0]."' AND PASSWORD='".hash('sha256', $data[1])."'";
        $result = $this->mysqli->query($query);
        $row = $result->fetch_array();

        if(is_null($row)) {
            echo "Erreur de connexion";
        }
        else {
            session_start();
            $_SESSION['userID'] = $row[0];
        }

        $this->mysqli->close();
    }


}



