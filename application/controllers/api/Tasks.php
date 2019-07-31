<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Tasks extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('tasks_m');
    }
    public function createTasks_post()
    {
        $taskData = $this->post();
        log_message("Debug", "createTasks" . json_encode($taskData));
        if (!$taskData) {
            $response = array('results' => false, 'code' => 400, 'message' => 'User data were not provided');
            log_message("Debug", "createUsers" . json_encode($response));
            $this->response($response, 400);
            exit();
        } else {
            $taskName = "";
            $taskDescription = "";
            $startDate = "";
            $endDate = "";
            $jiraLink = "";
            $taskStatus = "";
            $username = "";
            if (!empty($taskData)) {
                if (isset($taskData['task_name'])) {
                    $taskName = trim($taskData['task_name']);
                }
                if (isset($taskData['task_description'])) {
                    $taskDescription = trim($taskData['task_description']);
                }
                if (isset($taskData['start_date'])) {
                    $startDate = trim($taskData['start_date']);
                    $startDate = date("Y-m-d", strtotime($startDate));
                }
                if (isset($taskData['end_date'])) {
                    $endDate = trim($taskData['end_date']);
                    $endDate = date("Y-m-d", strtotime($endDate));
                }
                if (isset($taskData['task_jira_link'])) {
                    $jiraLink = trim($taskData['task_jira_link']);
                }
                if (isset($taskData['task_status'])) {
                    $taskStatus = trim($taskData['task_status']);
                }
                if (isset($taskData['username'])) {
                    $username = trim($taskData['username']);
                }
            }
            if (!$taskName) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task title is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$taskDescription) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task description is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$startDate) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task start date is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$username) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Username is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            $result = $this->tasks_m->createTasks(array('TASK_NAME' => $taskName,
                'TASK_DESCRIPTION' => $taskDescription,
                'START_DATE' => $startDate,
                'END_DATE' => $endDate,
                'TASK_JIRA_LINK' => $jiraLink,
                'TASK_STATUS' => $taskStatus,
                'USERNAME' => $username));
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("Debug", "createUsers" . json_encode($response));
                $this->response($response, 200);
                exit();
            } else {
                $response = array('results' => $result, 'code' => 400, 'message' => 'Failed');
                log_message("Debug", "createUsers" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
        }
    }

    /** +++++++++++++ API TO GET ALL TASKS BY ALL USERS ++++++++++++++++*/
    public function getTasks_get()
    {
        $result = $this->tasks_m->getTasks();
        if ($result) {
            $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
            log_message("DEBUG", "getTasks = " . json_encode($response));
            $this->response($response, 200);
            exit();
        } else {
            $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
            log_message("DEBUG", "getTasks = " . json_encode($response));
            $this->response($response, 404);
            exit();
        }
    }

        /** +++++++++++++++++++++++++ API TO GET BY USERNAME ++++++++++++++++*/
    public function getTasksPerUser_get()
    {
        $userName = $this->get('username');
        if (!$userName) {
            $response = array('results' => false, 'code' => 404, 'message' => 'Username is missing');
            log_message("Debug", "getTasksPerUser =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            $result = $this->tasks_m->getTasksPerUser($userName);
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("DEBUG", "getTasksPerUser = " . json_encode($response));
                $this->response($response, 200);
                exit;
            } else {
                $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
                log_message("DEBUG", "getTasksPerUser = " . json_encode($response));
                $this->response($response, 404);
                exit;
            }
        }
    }

        /** ++++++++++++++++++++++++++++++ API TO GET BY ID ++++++++++++++++*/
    public function getTasksById_get()
    {
        $taskId = $this->get('taskId');
        if (!$taskId) {
            $response = array('results' => false, 'code' => 404, 'message' => 'Task ID is missing');
            log_message("Debug", "getTasksById =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            $result = $this->tasks_m->getTasksById($taskId);
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("DEBUG", "getTasksById = " . json_encode($response));
                $this->response($response, 200);
                exit;
            } else {
                $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
                log_message("DEBUG", "getTasksById = " . json_encode($response));
                $this->response($response, 404);
                exit;
            }
        }
    }
    /** ++++++++++++++++++++++API TO DELETE SUB TASK++++++++++++++++++++++++*/
    public function deleteTaskById_get()
    {
        $taskId = $this->get('taskId');
        if (!$taskId) {
            $response = array('results' => false, 'code' => 404, 'message' => 'Task ID is missing');
            log_message("Debug", "deleteSubTaskById =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            $result = $this->tasks_m->deleteTaskById($taskId);
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("DEBUG", "deleteSubTaskById = " . json_encode($response));
                $this->response($response, 200);
                exit;
            } else {
                $response = array('results' => FALSE, 'code' => 404, 'message' => 'Failed');
                log_message("DEBUG", "deleteSubTaskById = " . json_encode($response));
                $this->response($response, 404);
                exit;
            }
        }
    }
    /**++++++++API UPDATE TASK+++++++++*/
    public function updateTask_post(){
        $taskData = $this->post();
        if (!$taskData){
            $response = array('results' => false, 'code' => 404, 'message' => 'Task Data are missing');
            log_message("Debug","UpdateTask = " .json_encode($response));
            $this->response($response,404);
            exit();
        }else{
            $taskId = "";
            $taskName = "";
            $taskDescription = "";
            $startDate = "";
            $endDate = "";
            $jiraLink = "";
            $taskStatus = "";
            $username = "";
            if (!empty($taskData)) {
                if (isset($taskData['task_id'])) {
                    $taskId = trim($taskData['task_id']);
                }
                if (isset($taskData['task_name'])) {
                    $taskName = trim($taskData['task_name']);
                }
                if (isset($taskData['task_description'])) {
                    $taskDescription = trim($taskData['task_description']);
                }
                if (isset($taskData['start_date'])) {
                    $startDate = trim($taskData['start_date']);
                    $startDate = date("Y-m-d", strtotime($startDate));
                }
                if (isset($taskData['end_date'])) {
                    $endDate = trim($taskData['end_date']);
                    $endDate = date("Y-m-d", strtotime($endDate));
                }
                if (isset($taskData['task_jira_link'])) {
                    $jiraLink = trim($taskData['task_jira_link']);
                }
                if (isset($taskData['task_status'])) {
                    $taskStatus = trim($taskData['task_status']);
                }
                if (isset($taskData['username'])) {
                    $username = trim($taskData['username']);
                }
            }
            if (!$taskId) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task ID is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$taskName) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task title is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$taskDescription) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task description is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$startDate) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task start date is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$username) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Username is missing');
                log_message("Debug", "createTasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            $result = $this->tasks_m->updateTask($taskId,array('TASK_NAME' => $taskName,
                'TASK_DESCRIPTION' => $taskDescription,
                'START_DATE' => $startDate,
                'END_DATE' => $endDate,
                'TASK_JIRA_LINK' => $jiraLink,
                'TASK_STATUS' => $taskStatus,
                'USERNAME' => $username));
            if ($result){
                $response = array('results'=>true,'code'=>200,'message'=>'Success');
                log_message("Debug", "updateTask=" . json_encode($response));
                $this->response($response, 200);
                exit();
            } else {
                $response = array('results' => $result, 'code' => 400, 'message' => 'Failed');
                log_message("Debug", "updateTask=" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
        }
    }
}
