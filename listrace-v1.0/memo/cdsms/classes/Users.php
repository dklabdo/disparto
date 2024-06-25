<?php
require_once('../config.php');

class Users extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function save_users(){
        extract($_POST);
        $data = '';
        foreach($_POST as $k => $v){
            if(in_array($k, array('firstname', 'lastname', 'username'))){
                if(!empty($data)) $data .= ", ";
                $data .= " {$k} = '{$v}' ";
            }
        }
        if(!empty($password)){
            $password = md5($password);
            if(!empty($data)) $data .= ", ";
            $data .= " `password` = '{$password}' ";
        }
        if(empty($id)){
            $qry = $this->conn->query("INSERT INTO users set {$data}");
            if($qry){
                $id = $this->conn->insert_id;
                $this->settings->set_flashdata('success','User Details successfully saved.');
                $resp['status'] = 1;
            } else {
                $resp['status'] = 2;
            }
        } else {
            $qry = $this->conn->query("UPDATE users set $data where id = {$id}");
            if($qry){
                $this->settings->set_flashdata('success',' Details successfully updated.');
                if($id == $this->settings->userdata('id')){
                    foreach($_POST as $k => $v){
                        if($k != 'id'){
                            $this->settings->set_userdata($k, $v);
                        }
                    }
                }
                $resp['status'] = 1;
            } else {
                $resp['status'] = 2;
            }
        }
        return $resp['status'];
    }
}

$users = new Users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'save':
        echo $users->save_users();
        break;
    default:
        // No action
        break;
}
?>
