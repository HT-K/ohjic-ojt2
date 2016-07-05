<?php

/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-16
 * Time: 오후 1:26
 */
class CalendarModel extends CI_Model
{
    function __construct()
    { // 생성자다, 초기화와 관련된 함수다.
        parent::__construct();
    }

    public function scheduleSelect($strDate)
    {
        $query = $this->db->get_where('calendar', array('start_date' => $strDate));

        return $query->result();
    }

    public function scheduleInsert($content, $startDate, $endDate)
    {
        $this->db->set(array(
            'content'=>$content,
            'start_date'=>$startDate,
            'end_date'=>$endDate));

        $this->db->insert('calendar'); // calendar 테이블에 insert!
    }

    public function scheduleSelectBySeq($seq)
    {
        $query = $this->db->get_where('calendar', array('seq' => $seq));

        return $query->result();
    }

    public function scheduleUpdate($seq, $content, $startDate, $endDate)
    {
        // 업데이트로 보낼 데이터 값
        $data = array (
            'content' => $content,
            'start_date' => $startDate,
            'end_date' => $endDate
        );

        $this->db->update('calendar', $data, array('seq'=>$seq)); // seq 값과 같은 row에 $data 값을 업데이트한다!
    }

    public function scheduleDelete($seq)
    {
        $this->db->delete('calendar', array('seq'=>$seq));
    }

}