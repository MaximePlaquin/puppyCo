<?php
include_once "database.php";

class users {
    public $msql;

    public function __construct() {
        $this->msql = DbFactory::create("mysql", "Cesi");
    }

    public function GET($request, $post) {
        $mArray = array();
        
        if(sizeof($request)<2) {
            $result = $this->msql->select("SELECT * FROM tpfinal_user");
        }
        else {
            $result = $this->msql->select("SELECT * FROM tpfinal_user WHERE ID=" . $request[1]);
        }


        while($row = $result->fetch_array()) {
            $mArray[] = $row;
        }
        echo json_encode($mArray);
    }


    
    public function POST($request, $post) {
        echo 'test';
        $this->msql->insert($post);
    }


    
    public function PUT($request, $post, $_PUT) {
        $query = "UPDATE TPFINAL_USER SET NOM='".$post["nom"]."', PRENOM='".$post["prenom"].", PSEUDO='".$post["pseudo"]."', MDP='".md5($post["mdp"])."' WHERE ID=".$request[1];
        echo $query;
        $this->msql->update($query);
    }


    public function DELETE($request, $post) {
        //var_dump($request);
        $query = "DELETE FROM TPFINAL_USER WHERE ID=".$request[1];
        echo $query;
        $this->msql->delete($query);
    }
}
?>