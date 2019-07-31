<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_m extends CI_Model
{
    public function getSequenceId($sequence)
    {
        $this->db->select($sequence);
        $qres = $this->db->get('DUAL');
        return $qres->row()->NEXTVAL;
    }

    public function createUser($data)
    {
        $data["USER_ID"] = $this->getSequenceId("REPORTSUMMARY.NEXTVAL");
        $query = $this->db->insert("TASKTRACKER.USERS", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getUsers()
    {
        $this->db->select('*');
        $this->db->from('TASKTRACKER.USERS');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function deleteUserByUserId($userId)
    {
        $this->db->where('USER_ID', $userId);
        $query = $this->db->delete('TASKTRACKER.USERS');
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    public function updateUsers($userId,$data){
        $this->db->where('USER_ID',$userId);
        $query = $this->db->update('TASKTRACKER.USERS',$data);
        if ($query){
            return true;
        }else{
            return false;
        }
    }
}
