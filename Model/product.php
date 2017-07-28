<?php

include_once "database.php";


class Product {
    public $reference;
    public $category_id;
    public $title;
    public $price;
    public $description;
    public $images;
    
    public $mysqli;



    public function __construct() {
        $this->images = array();
    }



    //-------------------------------Create---------------------------------
    public function create($reference, $title, $price, $descr, $category) {
        $this->mysqli = DbMySQL::getConnection();
        $this->reference = $reference;
        $this->title = $title;
        $this->price = $price;
        $this->description = $descr;

        $query = "SELECT ID FROM CATEGORIES WHERE NAME='".$category."'";
        $result = $this->mysqli->query($query);
        if($row = $result->fetch_array()) {
            $this->category_id = $row[0];
        }
        else {
            $query = "INSERT INTO CATEGORIES (NAME) VALUES ('".$category."')";
            $result = $this->mysqli->query($query);
            $this->category_id = $this->mysqli->insert_id;
        }

        $query = "INSERT INTO PRODUCTS (REFERENCE, CATEGORY_ID, TITLE, PRICE, DESCRIPTION) VALUES (".$this->reference.", '".$this->title."', ".$this->price.", '".$this->description."')";
        //echo $query."<br/>";
        $result = $this->mysqli->query($query);        
        //echo $result;
        $this->delivery_id = $this->mysqli->insert_id;


        $this->mysqli->commit();
        $this->mysqli->close();
    }




    //-------------------------------Read---------------------------------
    public function read($id) {
        $mArray = array();
        $this->mysqli = DbMySQL::getConnection();

        if($id!=null && $id>0) {
            $this->user_id = $id;
            $query = "SELECT * FROM USERS INNER JOIN DELIVERY_INFOS ON USERS.DELIVERY_INFO=DELIVERY_INFOS.ID WHERE USERS.ID = " .  $this->user_id;
            $result = $this->mysqli->query($query);  
            echo $query;
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
        $mysqli = database::getConnection();

        $query = "UPDATE USERS SET MAIL='".$data["mail"]."', PASSWORD='".$data["password"]."', STATUS='".$data["status"]."' WHERE ID=".$this->user_id;
        $result = $this->mysqli->query($query); 

        $query = "UPDATE DELIVERY_INFOS SET ADDRESS='".$data["address"]."', TYPE_CB='".$data["type_cb"]."', NUM_CB=".$data["num_cb"].", CRYPTO=".$data["crypto"].", POSTAL_CODE=".$data["postal_code"].", CITY='".$data["city"]."' WHERE ID=".$this->delivery_id;
        $result = $this->mysqli->query($query);  

        $mysqli->close();
        return $this->read($this->user_id);
    }




    //-------------------------------Delete---------------------------------
    public function delete() {
        $mysqli = database::getConnection();

        $query = "DELETE FROM USERS WHERE ID=".$this->user_id;
        $result = $this->mysqli->query($query); 

        $mysqli->close();
    }



}



$me = new Product();
$me->create(5645612, 'laisse', 10, 'une jolie laisse', 'promenade');