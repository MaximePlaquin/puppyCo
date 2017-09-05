<?php

include_once "database.php";


class Cart {
    public $cart_id;
    public $delivery_id;
    public $total_price;
    public $products;
    
    public $mysqli;



    public function __construct() {
        $this->products = array();
    }

    

    //-------------------------------Create---------------------------------
    public function create($data) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "INSERT INTO CARTS (TOTAL_PRICE, DATE_VALIDATION) VALUES (0, NULL)";
        //echo $query."<br/>";
        $result = $this->mysqli->query($query);        
        //echo $result;
        $this->cart_id = $this->mysqli->insert_id;


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
            $this->cart_id = $id;
            $query = "SELECT * FROM CARTS INNER JOIN CART_PRODUCTS ON CARTS.ID = CART_PRODUCTS.CART_ID INNER JOIN PRODUCTS ON PRODUCTS.REFERENCE = CART_PRODUCTS.PRODUCT_REFERENCE WHERE CARTS.ID = " .  $this->cart_id . " ORDER BY CARTS.ID ASC";
            //echo $query;
            $result = $this->mysqli->query($query);  
            while($row = $result->fetch_array()) {
                $mArray[] = $row;
            }
            $json = json_encode($mArray);
        }

        else {
            $query = "SELECT * FROM CARTS INNER JOIN CART_PRODUCTS ON CARTS.ID = CART_PRODUCTS.CART_ID INNER JOIN PRODUCTS ON PRODUCTS.REFERENCE = CART_PRODUCTS.PRODUCT_REFERENCE ORDER BY CARTS.ID ASC";
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

        $query = "UPDATE CARTS SET TOTAL_PRICE='".$data[1]."', DATE_VALIDATION='".$data[2]."' WHERE ID=".$this->cart_id;

        $result = $this->mysqli->query($query);
        
        $this->mysqli->close();
        return $this->read($this->cart_id);
    }




    //-------------------------------Delete---------------------------------
    public function delete($id) {
        $this->mysqli = DbMySQL::getConnection();

        $query = "DELETE FROM CARTS WHERE ID=".$id;
        $result = $this->mysqli->query($query);
        //echo $query;
        //echo $this->mysqli->error;

        $this->mysqli->close();
    }




    //-------------------------------AddProduct---------------------------------
    public function addProduct($data) {
        $this->read(array_slice($data, 0, 1));

        $this->mysqli = DbMySQL::getConnection();

        $query = "INSERT INTO CART_PRODUCTS (CART_ID, PRODUCT_REFERENCE, QUANTITY) VALUES (".$this->cart_id.", ".$data[1].", 1)";
        $result = $this->mysqli->query($query);
        //echo $query;
        //echo $this->mysqli->error;

        $this->mysqli->close();


    }

}



