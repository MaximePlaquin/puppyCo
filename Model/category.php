<?php

include_once "database.php";


class Category {
    public $id;
    public $name;
    
    public $mysqli;



    public function __construct() {
    }



    //-------------------------------Create---------------------------------
    public function create($data) {
        $this->mysqli = DbMySQL::getConnection();
        $this->name = $data[0];


        $query = "INSERT INTO CATEGORIES (NOM) VALUES ('".$this->name."')";
        $result = $this->mysqli->query($query);
        $this->id = $this->mysqli->insert_id;

        $this->mysqli->commit();
        $this->mysqli->close();
    }




    //-------------------------------Read---------------------------------
    public function read($data) {
        $this->mysqli = DbMySQL::getConnection();
        if(count($data)>0) {
            $this->id = $data[0];
        }

        if($this->id!=null && $this->id>0) {
            $query = "SELECT * FROM CATEGORIES WHERE CATEGORIES.ID = " .  $this->id;
            $result = $this->mysqli->query($query);  
            //echo $query;
            while($row = $result->fetch_array()) {
                $mArray[] = $row;
            }
            $this->name = $mArray[0][1];
            $json = json_encode($mArray);
        }

        else {
            $query = "SELECT * FROM CATEGORIES";
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
        $mysqli = DbMySQL::getConnection();

        $query = "UPDATE CATEGORIES SET NAME='".$data[1]."' WHERE ID=".$data[0];
        $result = $this->mysqli->query($query); 


        $mysqli->close();
        return $this->read($this->id);
    }




    //-------------------------------Delete---------------------------------
    public function delete($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "DELETE FROM CATEGORIES WHERE ID=".$data[0];
        $result = $this->mysqli->query($query); 

        $mysqli->close();
    }




    //----------------------------GetListProducts------------------------------
    public function getProductList($data) {
        $this->mysqli = DbMySQL::getConnection();
        $this->id = $data[0];
        $mArray = array();

        $query = "SELECT * FROM PRODUCTS WHERE CATEGORY_ID = " . $this->id;
        $result = $this->mysqli->query($query);

        while($row = $result->fetch_array()) {
            $mArray[] = $row;
        }
        $json = json_encode($mArray);
        


        $this->mysqli->commit();
        $this->mysqli->close();
        echo $json;
        return $json;
    }





}