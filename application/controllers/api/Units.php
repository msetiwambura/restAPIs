<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Units extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }
    function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('units_m');
    }

    public function createUnits_post()
    {
        $unitData = $this->post();
        log_message("Debug", "createUnits" . json_encode($unitData));
        if (!$unitData) {
            $response = array('results' => false, 'code' => 400, 'message' => 'Unit details are missing');
            log_message("Debug", "createUnits" . json_encode($response));
            $this->response($response, 400);
        } else {
            $unitID = "";
            $unitName = "";
            $unitDepartment = "";
            if (!empty($unitData)) {
                if (isset($unitData['unit_name'])) {
                    $unitName = trim($unitData['unit_name']);
                }
                if (isset($unitData['unit_department'])) {
                    $unitDepartment = trim($unitData['unit_department']);
                }
                if (isset($unitData['unit_id'])) {
                    $unitID = trim($unitData['unit_id']);
                }
            }
            if (!$unitName) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Unit name is missing');
                log_message("Debug", "createUnits" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$unitDepartment) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Unit Department is missing');
                log_message("Debug", "createUnits" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            if (!$unitID) {
                $response = array('results' => false, 'code' => 400, 'message' => 'Unit ID is missing');
                log_message("Debug", "createUnits" . json_encode($response));
                $this->response($response, 400);
                exit();
            }
            $result = $this->units_m->createUnits(
                array(
                    "UNIT_ID"=>$unitID,
                    "UNIT_NAME" => $unitName,
                    "DEPARTMENT" => $unitDepartment));
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
    public function getUnits_get(){
        $result = $this->units_m->getUnits();
        if ($result) {
            $response = array('results' => $result, 'code' => 200, 'message' => 'Success');
            log_message("DEBUG", "getUnits = " . json_encode($response));
            $this->response($response, 200);
            exit;
        } else {
            $response = array('results' => FALSE, 'code' => 400, 'message' => 'No data found');
            log_message("DEBUG", "getUnits = " . json_encode($response));
            $this->response($response, 400);
            exit;
        }
    }
}