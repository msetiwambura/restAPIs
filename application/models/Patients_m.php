<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients_m extends CI_Model
{

    public function registerPatients($data)
    {
        $query = $this->db->insert("patients", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getPatients()
    {
        $this->db->select('*');
        $this->db->from('patients');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function getTasksPerUser($username)
    {
        $this->db->select('A.*,B.NAME');
        $this->db->from('TASKTRACKER.TASKS A');
        $this->db->join('TASKTRACKER.USERS B','A.USERNAME =B.USERNAME');
        $this->db->where('A.USERNAME', $username);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function getTasksById($taskId)
    {
        $this->db->select('*');
        $this->db->from('TASKTRACKER.TASKS');
        $this->db->where('TASK_ID', $taskId);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    public function deleteTaskById($taskId)
    {
        $this->db->where('TASK_ID', $taskId);
        $query = $this->db->delete('TASKTRACKER.TASKS');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    public function updateTask($taskId,$data){
        $startDate = $data['START_DATE'];
        $endDate = $data['END_DATE'];
        unset($data['END_DATE']);
        unset($data['START_DATE']);
        $this->db->where('TASK_ID',$taskId);
        $this->db->set("START_DATE", "TO_DATE('$startDate','YYYY-MM-DD')", false);
        $this->db->set("END_DATE", "TO_DATE('$endDate','YYYY-MM-DD')", false);
        $query = $this->db->update("TASKTRACKER.TASKS", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
}
