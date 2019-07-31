<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Comments extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }
    function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('comments_m');
    }
    public function createComments_post()
    {
        $commentData = $this->post();
        log_message("Debug", "createComments" . json_encode($commentData));
        if (!$commentData) {
            $response = array('results' => false, 'code' => 400, 'message' => 'Comment details are missing');
            log_message("Debug", "createComments" . json_encode($response));
            $this->response($response, 400);
        } else {
            $userComment = "";
            $subTaskId= "";
            $commentDate = "";
            if (!empty($commentData)) {
                if (isset($commentData['user_comment'])) {
                    $userComment = trim($commentData['user_comment']);
                }
                if (isset($commentData['sub_taskId'])) {
                    $subTaskId = trim($commentData['sub_taskId']);
                }
                if (isset($commentData['sub_taskId'])) {
                    $commentDate = trim($commentData['date_comment']);
                    $commentDate = date("Y-m-d", strtotime($commentDate));
                }
            }
            if (!$userComment) {
                $response = array('results' => false, 'code' => 400, 'message' => 'User comment is missing');
                log_message("Debug", "createComments" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$commentDate) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Comment date is missing');
                log_message("Debug", "createComments" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$subTaskId) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Sub task id is missing');
                log_message("Debug", "createComments" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            $result = $this->comments_m->createComments(
                array(
                    "USERCOMMENT" => $userComment,
                    "DATE_COMMENT" => $commentDate,
                    "SUBTASK_ID" => $subTaskId));
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
