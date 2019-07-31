<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Subtasks extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }
    function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('subtasks_m');
    }
    public function createSubtasks_post()
    {
        $subTaskData = $this->post();
        log_message("Debug", "createSubtasks" . json_encode($subTaskData));
        if (!$subTaskData) {
            $response = array('results' => false, 'code' => 400, 'message' => 'User data were not provided');
            log_message("Debug", "createUsers" . json_encode($response));
            $this->response($response, 400);
            exit();
        } else {
            $subTaskName = "";
            $subTaskDescription = "";
            $startDate = "";
            $endDate = "";
            $subTaskJiraLink = "";
            $subTaskStatus = "";
            $taskId = "";
            if (!empty($subTaskData)) {
                if (isset($subTaskData['sub_task_name'])) {
                    $subTaskName = trim($subTaskData['sub_task_name']);
                }
                if (isset($subTaskData['sub_task_description'])) {
                    $subTaskDescription = trim($subTaskData['sub_task_description']);
                }
                if (isset($subTaskData['start_date'])) {
                    $startDate = trim($subTaskData['start_date']);
                    $startDate = date("Y-m-d", strtotime($startDate));
                }
                if (isset($subTaskData['end_date'])) {
                    $endDate = trim($subTaskData['end_date']);
                    $endDate = date("Y-m-d", strtotime($endDate));
                }
                if (isset($subTaskData['sub_task_jira_link'])) {
                    $subTaskJiraLink = trim($subTaskData['sub_task_jira_link']);
                }
                if (isset($subTaskData['sub_task_status'])) {
                    $subTaskStatus = trim($subTaskData['sub_task_status']);
                }
                if (isset($subTaskData['task_id'])) {
                    $taskId = trim($subTaskData['task_id']);
                }
            }
            if (!$subTaskName) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Sub task title is missing');
                log_message("Debug", "createSubtasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$subTaskDescription) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task description is missing');
                log_message("Debug", "createSubtasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$startDate) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task start date is missing');
                log_message("Debug", "createSubtasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$taskId) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task ID is missing');
                log_message("Debug", "createSubtasks" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            $result = $this->subtasks_m->createSubtasks(array('SUBTASK_NAME' => $subTaskName,
                'SUBTASK_DESCRIPTION' => $subTaskDescription,
                'START_DATE' => $startDate,
                'END_DATE' => $endDate,
                'SUBTASK_JIRA_LINK' => $subTaskJiraLink,
                'SUBTASK_STATUS' => $subTaskStatus,
                'TASK_ID' => $taskId));
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

    /** +++++++++++++API TO GET ALL SUB TASKS BY ALL USERS++++++++++++++++*/
    public function getSubTasks_get()
    {
        $result = $this->subtasks_m->getSubTasks();
        if ($result) {
            $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
            log_message("DEBUG", "getSubTasks = " . json_encode($response));
            $this->response($response, 200);
            exit();
        } else {
            $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
            log_message("DEBUG", "getSubTasks = " . json_encode($response));
            $this->response($response, 404);
            exit();
        }
    }

    /** +++++++++++++API TO GET ALL SUB TASKS PER INDIVIDUALS ++++++++++++++++*/
    public function getSubTasksPerUser_get()
    {
        $userName = $this->get('username');
        if (!$userName) {
            $response = array('results' => false, 'code' => 404, 'message' => 'Username is missing');
            log_message("Debug", "getSubTasksPerUser =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            if (!$userName) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Please supply the username');
                log_message("DEBUG", "getSubTasksPerUser = " . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            $result = $this->subtasks_m->getSubTasksPerUser($userName);
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("DEBUG", "getSubTasksPerUser = " . json_encode($response));
                $this->response($response, 200);
                exit;
            } else {
                $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
                log_message("DEBUG", "getSubTasksPerUser = " . json_encode($response));
                $this->response($response, 404);
                exit;
            }
        }
    }

    /** ++++++++++++++++++++++++++++++ API TO GET BY ID ++++++++++++++++*/
    public function getSubTasksById_get()
    {
        $subTaskId = $this->get('subTaskId');
        if (!$subTaskId) {
            $response = array('results' => false, 'code' => 404, 'message' => 'Sub Task ID is missing');
            log_message("Debug", "getSubTasksById =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            $result = $this->subtasks_m->getSubTasksById($subTaskId);
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("DEBUG", "getSubTasksById = " . json_encode($response));
                $this->response($response, 200);
                exit;
            } else {
                $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
                log_message("DEBUG", "getSubTasksById = " . json_encode($response));
                $this->response($response, 404);
                exit;
            }
        }
    }
    /** ++++++++++++++++++++++++++++++ API TO GET BY getSubTasksByTaskId ++++++++++++++++*/
    public function getSubTasksByTaskId_get()
    {
        $taskId = $this->get('taskId');
        if (!$taskId) {
            $response = array('results' => false, 'code' => 404, 'message' => 'Sub Task ID is missing');
            log_message("Debug", "getSubTasksByTaskId =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            $result = $this->subtasks_m->getSubTasksByTaskId($taskId);
            if ($result) {
                $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
                log_message("DEBUG", "getSubTasksByTaskId = " . json_encode($response));
                $this->response($response, 200);
                exit;
            } else {
                $response = array('results' => FALSE, 'code' => 404, 'message' => 'No data found');
                log_message("DEBUG", "getSubTasksByTaskId = " . json_encode($response));
                $this->response($response, 404);
                exit;
            }
        }
    }
    /** ++++++++++++++++++++++API TO DELETE SUB TASK++++++++++++++++++++++++*/
    public function deleteSubTaskById_get()
    {
        $subTaskId = $this->get('subTaskId');
        if (!$subTaskId) {
            $response = array('results' => false, 'code' => 404, 'message' => 'Sub Task ID is missing');
            log_message("Debug", "deleteSubTaskById =" . json_encode($response));
            $this->response($response, 404);
            exit();
        } else {
            $result = $this->subtasks_m->deleteSubTaskById($subTaskId);
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
    //UPDATE SUB TASKS
    public function updateSubTask_post(){
        $subTaskData = $this->post();
        if (!$subTaskData){
            $response = array('results' => false, 'code' => 404, 'message' => 'Sub Task Data are missing');
            log_message("Debug","UpdateTask = " .json_encode($response));
            $this->response($response,404);
            exit();
        }else{
            $subTaskName = "";
            $subTaskDescription = "";
            $startDate = "";
            $endDate = "";
            $subTaskJiraLink = "";
            $subTaskStatus = "";
            $subTaskId = "";
            if (!empty($subTaskData)) {
                if (isset($subTaskData['sub_task_name'])) {
                    $subTaskName = trim($subTaskData['sub_task_name']);
                }
                if (isset($subTaskData['sub_task_description'])) {
                    $subTaskDescription = trim($subTaskData['sub_task_description']);
                }
                if (isset($subTaskData['start_date'])) {
                    $startDate = trim($subTaskData['start_date']);
                    $startDate = date("Y-m-d", strtotime($startDate));
                }
                if (isset($subTaskData['end_date'])) {
                    $endDate = trim($subTaskData['end_date']);
                    $endDate = date("Y-m-d", strtotime($endDate));
                }
                if (isset($subTaskData['sub_task_jira_link'])) {
                    $subTaskJiraLink = trim($subTaskData['sub_task_jira_link']);
                }
                if (isset($subTaskData['sub_task_status'])) {
                    $subTaskStatus = trim($subTaskData['sub_task_status']);
                }
                if (isset($subTaskData['sub_task_id'])) {
                    $subTaskId = trim($subTaskData['sub_task_id']);
                }
            }
            if (!$subTaskName) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Sub task title is missing');
                log_message("Debug", "updateSubTask" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$subTaskDescription) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task description is missing');
                log_message("Debug", "updateSubTask" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$startDate) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task start date is missing');
                log_message("Debug", "updateSubTask" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$subTaskId) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Task ID is missing');
                log_message("Debug", "updateSubTask" . json_encode($response));
                $this->response($response, 400);
                exit();
            }

            $result = $this->subtasks_m->updateSubTask($subTaskId,array('SUBTASK_NAME' => $subTaskName,
                'SUBTASK_DESCRIPTION' => $subTaskDescription,
                'START_DATE' => $startDate,
                'END_DATE' => $endDate,
                'SUBTASK_JIRA_LINK' => $subTaskJiraLink,
                'SUBTASK_STATUS' => $subTaskStatus));
            if ($result){
                $response = array('results'=>true,'code'=>200,'message'=>'Success');
                log_message("Debug", "updateSubTask=" . json_encode($response));
                $this->response($response, 200);
                exit();
            } else {
                $response = array('results' => $result, 'code' => 400, 'message' => 'Failed');
                log_message("Debug", "updateSubTask=" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
        }
    }
}
