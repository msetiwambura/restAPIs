<?php

class Subtasks_m extends CI_Model
{
    public function getSequenceId($sequence)
    {
        $this->db->select($sequence);
        $qres = $this->db->get('DUAL');
        return $qres->row()->NEXTVAL;
    }

    public function createSubTasks($data)
    {
        $data["SUBTASK_ID"] = $this->getSequenceId("REPORTSUMMARY.NEXTVAL");
        $startDate = $data['START_DATE'];
        $endDate = $data['END_DATE'];
        unset($data['END_DATE']);
        unset($data['START_DATE']);
        $this->db->set("START_DATE", "TO_DATE('$startDate','YYYY-MM-DD')", false);
        $this->db->set("END_DATE", "TO_DATE('$endDate','YYYY-MM-DD')", false);
        $query = $this->db->insert("TASKTRACKER.SUBTASKS", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getSubTasks()
    {
        $this->db->select('*');
        $this->db->from('TASKTRACKER.SUBTASKS');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function getSubTasksPerUser($username)
    {
        $this->db->select('A.*,B.USERNAME');
        $this->db->from('TASKTRACKER.SUBTASKS A');
        $this->db->join('TASKTRACKER.TASKS B', 'A.TASK_ID = B.TASK_ID');
        $this->db->where('B.USERNAME', $username);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function getSubTasksById($taskId)
    {
        $this->db->select('*');
        $this->db->from('TASKTRACKER.SUBTASKS');
        $this->db->where('SUBTASK_ID', $taskId);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    public function getSubTasksByTaskId($taskId)
    {
        $this->db->select('A.*,B.*');
        $this->db->from('TASKTRACKER.SUBTASKS A');
        $this->db->join('TASKTRACKER.TASKS B', 'B.TASK_ID = A.TASK_ID');
        $this->db->where('A.TASK_ID', $taskId);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    public function deleteSubTaskById($subTaskId)
    {
        $this->db->where('SUBTASK_ID', $subTaskId);
        $query = $this->db->delete('TASKTRACKER.SUBTASKS');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    public function updateSubTask($subTaskId,$data){
        $startDate = $data['START_DATE'];
        $endDate = $data['END_DATE'];
        unset($data['END_DATE']);
        unset($data['START_DATE']);
        $this->db->where('SUBTASK_ID',$subTaskId);
        $this->db->set("START_DATE", "TO_DATE('$startDate','YYYY-MM-DD')", false);
        $this->db->set("END_DATE", "TO_DATE('$endDate','YYYY-MM-DD')", false);
        $query = $this->db->update("TASKTRACKER.SUBTASKS", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
}
