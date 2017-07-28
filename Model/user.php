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



    //-----------------------Methods---------------------------
    public function create($mail, $mdp) {
        $this->mysqli = DbMySQL::getConnection();
        $this->mail = $mail;
        $this->mdp = $mdp;
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
        echo $query."<br/>";
        $result =  $this->mysqli->query($query);
        $this->user_id = $this->mysqli->insert_id;

        $this->mysqli->commit();
        $this->mysqli->close();
    }



    public function read($id) {
        $mArray = array();
        $this->mysqli = DbMySQL::getConnection();

        if($id!=null && $id>0) {
            $this->user_id = $id;
            $query = "SELECT * FROM USERS INNER JOIN DELIVERY_INFOS ON USERS.DELIVERY_INFO=DELIVERY_INFOS.ID WHERE USERS.ID = " .  $this->user_id;
            $result = $this->mysqli->query($query);  
            echo $this->mysqli->error;
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



    public function update($data) {
        $mysqli = database::getConnection();

        $query = "UPDATE USERS SET MAIL='".$data["mail"]."', PASSWORD='".$data["password"]."', STATUS='".$data["status"]."' WHERE ID=".$this->user_id;
        $result = $this->mysqli->query($query);  

        $mysqli->close();
    }



    public function delete() {
        $mysqli = database::getConnection();


        $mysqli->close();
    }



}


$me = new User();
$me->read(0);