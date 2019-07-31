<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Units_m extends CI_Model
{
    public function createUnits($data)
    {
        $query = $this->db->insert("TASKTRACKER.UNITS", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getUnits()
    {
        $this->db->select('*');
        $this->db->from('TASKTRACKER.UNITS');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return 0;
        }
    }
}