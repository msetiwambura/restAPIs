<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Users extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }
    function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('users_m');
    }
    public function createUsers_post(){
        $userData = $this->post();
        log_message("DEBUG","createUsers".json_encode($userData));
        if (!$userData){
            $response = array('results'=>false,'code'=>400,'message'=>'User data were not provided');
            log_message("Debug","createUsers".json_encode($response));
            $this->response($response,400);
            exit();
        }else{
            $name = "";
            $username = "";
            $userEmail = "";
            $userPhone = "";
            $active = 1;
            $unitID = "";
            $role_id = "";
            if (!empty($userData)){
                if (isset($userData['name'])){
                    $name = trim($userData['name']);
                }
                if (isset($userData['username'])){
                    $username = trim($userData['username']);
                }
                if (isset($userData['user_email'])){
                    $userEmail = trim($userData['user_email']);
                }
                if (isset($userData['user_phone'])){
                    $userPhone = trim($userData['user_phone']);
                }
                if (isset($userData['active'])){
                    $active = trim($userData['active']);
                }
                if (isset($userData['unit_id'])){
                    $unitID = trim($userData['unit_id']);
                }
                if (isset($userData['role_id'])){
                    $role_id = trim($userData['role_id']);
                }
            }
            if (!$name){
                $response = array('results'=>false,'code'=>400,'message'=>'Name is Mandatory');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$username){
                $response = array('results'=>false,'code'=>400,'message'=>'Username is Mandatory');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$userEmail){
                $response = array('results'=>false,'code'=>400,'message'=>'User Mail is Mandatory');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$unitID){
                $response = array('results'=>false,'code'=>400,'message'=>'Unit ID is Mandatory');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$role_id){
                $response = array('results'=>false,'code'=>400,'message'=>'User Role is Mandatory');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            $result = $this->users_m->createUser(array(
                "NAME"=> $name,
                "USERNAME" => $username,
                "ACTIVE" => $active,
                "ROLE_ID" => $role_id,
                "USER_EMAIL" => $userEmail,
                "USER_PHONE" => $userPhone,
                "UNIT_ID" => $unitID));
            if ($result){
                $response = array('results'=>$result,'code'=>200,'message'=>'Success');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,200);
                exit();
            }else{
                $response = array('results'=>$result,'code'=>400,'message'=>'Failed');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
        }
    }
    /** +++++++++++++API TO GET ALL TASKS BY ALL USERS++++++++++++++++*/
    public function getUsers_get()
    {
        $result = $this->users_m->getUsers();
        if ($result) {
            $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
            log_message("DEBUG", "getUsers = " . json_encode($response));
            $this->response($response, 200);
            exit();
        } else {
            $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
            log_message("DEBUG", "getUsers = " . json_encode($response));
            $this->response($response, 404);
            exit();
        }
    }
    /** +++++++++++++API TO GET ALL TASKS PER INDIVIDUALS++++++++++++++++*/
    public function getSingleUser_get()
    {
        $userName = $this->get('username');
        if ($userName) {
            $response = array('results' => false, 'code' => 400, 'message' => 'Please supply the username');
            log_message("DEBUG", "getSingleUser = " . json_encode($response));
            $this->response($response, 400);
            exit();
        }
        $result = $this->users_m->getSingleUser($userName);
        if ($result) {
            $response = array('results' => $result, 'code' => $this->successCode, 'message' => 'Success');
            log_message("DEBUG", "getSingleUser = " . json_encode($response));
            $this->response($response, 200);
            exit;
        } else {
            $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
            log_message("DEBUG", "getSingleUser = " . json_encode($response));
            $this->response($response, 404);
            exit;
        }
    }
    ##  DELETE USER
    public function deleteUserByUserId_get()
    {
        $userId = $this->get('user_id');
        if (!$userId) {
            $response = array('results' => false, 'code' => 404, 'message' => 'USER ID is missing');
            log_message("Debug", "deleteUserByUserId =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            $result = $this->users_m->deleteUserByUserId($userId);
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("DEBUG", "deleteUserByUserId = " . json_encode($response));
                $this->response($response, 200);
                exit;
            } else {
                $response = array('results' => FALSE, 'code' => 404, 'message' => 'Failed');
                log_message("DEBUG", "deleteUserByUserId = " . json_encode($response));
                $this->response($response, 404);
                exit;
            }
        }
    }
    public function updateUsers_post(){
        $userData = $this->post();
        log_message("DEBUG","updateUsers".json_encode($userData));
        if (!$userData){
            $response = array('results'=>false,'code'=>400,'message'=>'User data were not provided');
            log_message("Debug","updateUsers".json_encode($response));
            $this->response($response,400);
            exit();
        }else{
            $userId = "";
            $name = "";
            $username = "";
            $userEmail = "";
            $userPhone = "";
            $active = 1;
            $unitID = "";
            $role_id = "";
            if (!empty($userData)){
                if (isset($userData['user_id'])){
                    $userId = trim($userData['user_id']);
                }
                if (isset($userData['name'])){
                    $name = trim($userData['name']);
                }
                if (isset($userData['username'])){
                    $username = trim($userData['username']);
                }
                if (isset($userData['user_email'])){
                    $userEmail = trim($userData['user_email']);
                }
                if (isset($userData['user_phone'])){
                    $userPhone = trim($userData['user_phone']);
                }
                if (isset($userData['active'])){
                    $active = trim($userData['active']);
                }
                if (isset($userData['unit_id'])){
                    $unitID = trim($userData['unit_id']);
                }
                if (isset($userData['role_id'])){
                    $role_id = trim($userData['role_id']);
                }
            }
            if (!$userId){
                $response = array('results'=>false,'code'=>400,'message'=>'Id is Mandatory');
                log_message("Debug","updateUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }if (!$name){
                $response = array('results'=>false,'code'=>400,'message'=>'Name is Mandatory');
                log_message("Debug","updateUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$username){
                $response = array('results'=>false,'code'=>400,'message'=>'Username is Mandatory');
                log_message("Debug","updateUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$userEmail){
                $response = array('results'=>false,'code'=>400,'message'=>'User Mail is Mandatory');
                log_message("Debug","updateUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$unitID){
                $response = array('results'=>false,'code'=>400,'message'=>'Unit ID is Mandatory');
                log_message("Debug","updateUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            if (!$role_id){
                $response = array('results'=>false,'code'=>400,'message'=>'User Role is Mandatory');
                log_message("Debug","updateUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
            $result = $this->users_m->updateUsers($userId,array(
                "NAME"=> $name,
                "USERNAME" => $username,
                "ACTIVE" => $active,
                "ROLE_ID" => $role_id,
                "USER_EMAIL" => $userEmail,
                "USER_PHONE" => $userPhone,
                "UNIT_ID" => $unitID));
            if ($result){
                $response = array('results'=>$result,'code'=>200,'message'=>'Success');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,200);
                exit();
            }else{
                $response = array('results'=>$result,'code'=>400,'message'=>'Failed');
                log_message("Debug","createUsers".json_encode($response));
                $this->response($response,400);
                exit();
            }
        }
    }
}
