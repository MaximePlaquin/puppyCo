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

        session_start();

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
            $query = "SELECT * FROM CARTS INNER JOIN CART_PRODUCTS ON CARTS.ID = CART_PRODUCTS.CART_ID INNER JOIN PRODUCTS ON PRODUCTS.REFERENCE = CART_PRODUCTS.PRODUCT_REFERENCE WHERE CARTS.ID = " .  $id . " ORDER BY CARTS.ID ASC";
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






        //-------------------------------cartFromUser---------------------------------
        public function getProductsFromUser($data) {
    
            $mArray = array();
            $this->mysqli = DbMySQL::getConnection();
    
            $query = "SELECT SUM(QUANTITY)*PRICE AS TOTAL_PRICE, CARTS.ID, REFERENCE, TITLE, PRICE, DESCRIPTION, SUM(QUANTITY) AS QUANTITY, URL FROM USERS INNER JOIN CARTS ON CARTS.ID = USERS.CART_ID INNER JOIN CART_PRODUCTS ON CARTS.ID = CART_PRODUCTS.CART_ID INNER JOIN PRODUCTS ON PRODUCTS.REFERENCE = CART_PRODUCTS.PRODUCT_REFERENCE INNER JOIN IMAGES ON PRODUCTS.REFERENCE=IMAGES.PRODUCT_REFERENCE WHERE USERS.ID = " .  $_SESSION['userID'] . " GROUP BY PRODUCTS.REFERENCE ORDER BY PRODUCTS.REFERENCE ASC";
            //echo $query;
            $result = $this->mysqli->query($query);  
            while($row = $result->fetch_array()) {
                $mArray[] = $row;
            }
            $json = json_encode($mArray);
    
            $this->mysqli->commit();
            $this->mysqli->close();
            echo $json;
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
    public function delete($data) {


        $this->mysqli = DbMySQL::getConnection();
        $user = new User();
        $user->read($_SESSION['userID']);

        $query = "DELETE FROM CART_PRODUCTS WHERE PRODUCT_REFERENCE=".$data[0]." AND CART_ID=".$user->cart_id;
        $result = $this->mysqli->query($query);
        //echo $query;
        //echo $this->mysqli->error;

        $this->mysqli->close();
        header("Location: /PuppyCo/View/panier.html");
        die();
    }




    //-------------------------------AddProduct---------------------------------
    public function addProduct($data) {
        $this->mysqli = DbMySQL::getConnection();


        $query = "SELECT USERS.ID, MAIL, PASSWORD, CART_ID, DELIVERY_INFO, STATUS, ADDRESS, TYPE_CB, NUM_CB, CRYPTO, POSTAL_CODE, CITY FROM USERS INNER JOIN DELIVERY_INFOS ON USERS.DELIVERY_INFO=DELIVERY_INFOS.ID WHERE USERS.ID=".$_SESSION['userID'];
        $result = $this->mysqli->query($query);  

        while($row = $result->fetch_array()) {
            $mArray[] = $row;
        }

        $query = "INSERT INTO CART_PRODUCTS (CART_ID, PRODUCT_REFERENCE, QUANTITY) VALUES (".$mArray[0][3].", ".$data[0].", 1)";
        $result = $this->mysqli->query($query);
        //echo $query;
        //echo $this->mysqli->error;

        $this->mysqli->close();

        header("Location: /PuppyCo/View/panier.html");
        die();
    }

}



