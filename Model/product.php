<?php

include_once "database.php";


class Product {
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
    public function create($data) {
        $reference = $data[0];
        $title = $data[1];
        $price = $data[2];
        $descr = $data[3];
        $category = $data[4];
        $this->mysqli = DbMySQL::getConnection();
        $this->reference = $reference;
        $this->category = $category;
        $this->title = $title;
        $this->price = $price;
        $this->description = $descr;

        $query = "SELECT ID FROM CATEGORIES WHERE NOM='".$category."'";
        $result = $this->mysqli->query($query);
        echo $this->mysqli->error;
        if($row = $result->fetch_array()) {
            $this->category_id = $row[0];
        }
        else {
            $query = "INSERT INTO CATEGORIES (NOM) VALUES ('".$category."')";
            $result = $this->mysqli->query($query);
            $this->category_id = $this->mysqli->insert_id;
        }

        $query = "INSERT INTO PRODUCTS (REFERENCE, CATEGORY_ID, TITLE, PRICE, DESCRIPTION) VALUES (".$this->reference.", ".$this->category_id.", '".$this->title."', ".$this->price.", '".$this->description."')";
        $result = $this->mysqli->query($query);

        $this->mysqli->commit();
        $this->mysqli->close();
    }




    //-------------------------------Read---------------------------------
    public function read($data) {
        $ref = 0;
        $mArray = array();
        $this->mysqli = DbMySQL::getConnection();

        if(count($data) == 1) {
            $ref = $data[0];
        }

        if($ref!=null && $ref>0) {
            $this->reference = $ref;
            $query = "SELECT * FROM PRODUCTS INNER JOIN CATEGORIES ON PRODUCTS.CATEGORY_ID=CATEGORIES.ID INNER JOIN IMAGES ON PRODUCTS.REFERENCE=IMAGES.PRODUCT_REFERENCE WHERE PRODUCTS.REFERENCE = " .  $this->reference;
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
            if(count($data) > 1) {
                //retourne data[1] elements a partir de data[0]
                $query = "SELECT * FROM PRODUCTS INNER JOIN CATEGORIES ON PRODUCTS.CATEGORY_ID=CATEGORIES.ID INNER JOIN IMAGES ON PRODUCTS.REFERENCE=IMAGES.PRODUCT_REFERENCE  ORDER BY REFERENCE ASC LIMIT ".$data[1]." OFFSET ".$data[0];
            }
            else {
                $query = "SELECT * FROM PRODUCTS INNER JOIN CATEGORIES ON PRODUCTS.CATEGORY_ID=CATEGORIES.ID INNER JOIN IMAGES ON PRODUCTS.REFERENCE=IMAGES.PRODUCT_REFERENCE";
            }
            $result = $this->mysqli->query($query);  
            //echo $query;
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
        $this->mysqli = DbMySQL::getConnection();

        $query = "UPDATE PRODUCTS SET CATEGORY_ID=".$data[1].", TITLE='".$data[2]."', PRICE=".$data[3].", DESCRIPTION=".$data[4]." WHERE REFERENCE=".$data[0];
        $result = $this->mysqli->query($query); 

        $mysqli->close();
        return $this->read($this->user_id);
    }



    //-------------------------------Delete---------------------------------
    public function delete($data) {
        $this->mysqli = DbMySQL::getConnection();

        foreach($data as $d) {
            $query = "DELETE FROM PRODUCTS WHERE ID=".$d;
            $result = $this->mysqli->query($query); 
        }

        $mysqli->close();
    }





    //-------------------------------addImage---------------------------------
    public function addImage($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "INSERT INTO IMAGES (PRODUCT_REFERENCE, URL) VALUES (".$data[0].", '".$data[1]."')";
        $result = $this->mysqli->query($query); 

        $mysqli->close();
    }





    //-------------------------------getRandom---------------------------------
    public function getRandom($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "SELECT URL, TITLE, PRODUCTS.REFERENCE FROM PRODUCTS INNER JOIN CATEGORIES ON PRODUCTS.CATEGORY_ID=CATEGORIES.ID INNER JOIN IMAGES ON PRODUCTS.REFERENCE=IMAGES.PRODUCT_REFERENCE ORDER BY RAND() LIMIT ".$data[0];
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





    //-------------------------------Search---------------------------------
    public function search($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "SELECT * FROM PRODUCTS INNER JOIN CATEGORIES ON PRODUCTS.CATEGORY_ID=CATEGORIES.ID INNER JOIN IMAGES ON PRODUCTS.REFERENCE=IMAGES.PRODUCT_REFERENCE WHERE TITLE LIKE '%".$data[0]."%' OR DESCRIPTION LIKE '%".$data[0]."%'";
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







    //-------------------------------GetByCategory---------------------------------
    public function getByCategory($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "SELECT url, title, products.reference, nom, price, description FROM PRODUCTS INNER JOIN CATEGORIES ON PRODUCTS.CATEGORY_ID=CATEGORIES.ID INNER JOIN IMAGES ON PRODUCTS.REFERENCE=IMAGES.PRODUCT_REFERENCE WHERE CATEGORIES.NOM='".$data[0]. "' ORDER BY REFERENCE ASC LIMIT ".$data[2]." OFFSET ".$data[1];
        $result = $this->mysqli->query($query);  
        //echo $query;
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


