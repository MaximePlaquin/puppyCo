<?php

include_once "database.php";


class Category {
    public $reference;
    public $category_id;
    public $category;
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
        $this->category = $category;
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

        $query = "INSERT INTO PRODUCTS (REFERENCE, CATEGORY_ID, TITLE, PRICE, DESCRIPTION) VALUES (".$this->reference.", ".$this->category_id.", '".$this->title."', ".$this->price.", '".$this->description."')";
        $result = $this->mysqli->query($query);

        $this->mysqli->commit();
        $this->mysqli->close();
    }




    //-------------------------------Read---------------------------------
    public function read($ref) {
        $mArray = array();
        $this->mysqli = DbMySQL::getConnection();

        if($ref!=null && $ref>0) {
            $this->reference = $ref;
            $query = "SELECT * FROM PRODUCTS INNER JOIN CATEGORIES ON PRODUCTS.CATEGORY_ID=CATEGORIES.ID WHERE PRODUCTS.REFERENCE = " .  $this->reference;
            $result = $this->mysqli->query($query);  
            //echo $query;
            while($row = $result->fetch_array()) {
                $mArray[] = $row;
            }
            $this->reference = $mArray[0][0];
            $this->title = $mArray[0][2];
            $this->price = $mArray[0][3];
            $this->description = $mArray[0][4];
            $this->category = $mArray[0][5];
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
$me->read(564452);