<?php
class Comments_m extends CI_Model{
    public function getSequenceId($sequence)
    {
        $this->db->select($sequence);
        $qres = $this->db->get('DUAL');
        return $qres->row()->NEXTVAL;
    }
    public function createComments($data){
        $data["COMMENT_ID"] = $this->getSequenceId("REPORTSUMMARY.NEXTVAL");
        $commentDate =  $data['DATE_COMMENT'];
        unset($data['DATE_COMMENT']);
        $this->db->set("DATE_COMMENT", "TO_DATE('$commentDate','YYYY-MM-DD')", false);
        $query = $this->db->insert("TASKTRACKER.USERCOMMENTS", $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
}
