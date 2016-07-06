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

    public function scheduleSelect($startDate, $endDate)
    {
        $startDate = strtotime($startDate);
        $startDate = date('Y-m-d', $startDate);

        $endDate = strtotime($endDate);
        $endDate = date('Y-m-d', $endDate);

        //$query = $this->db->get_where('calendar', array('start_date' => $strDate));

        // %c는 2016-01-01   => 왼쪽 날짜를 2016-1-01 로 인식하게 바꿔주고
        // %e는 2016-01-01   => 왼쪽 날짜를 2016-01-1 로 인식하게 바꿔준다.
        $sql = "SELECT seq as seq,
                        content as content,
                        DATE_FORMAT(start_date,'%Y-%c-%e') as start_date,
                        DATE_FORMAT(end_date,'%Y-%c-%e') as end_date
                FROM calendar
                where start_date  between ? and ?";

        $query = $this->db->query($sql, array($startDate, $endDate));

        return $query->result();
    }

    public function scheduleInsert($content, $startDate, $endDate)
    {
        // $startDate와 endDate 문자열 값을 date 형식으로 바꿔주는 작업이다!
        $startDate = strtotime($startDate);
        $startDate = date('Y-m-d', $startDate);

        $endDate = strtotime($endDate);
        $endDate = date('Y-m-d', $endDate);

        $this->db->set(array(
            'content'=>$content,
            'start_date'=>$startDate,
            'end_date'=>$endDate));

        $this->db->insert('calendar'); // calendar 테이블에 insert!
    }

    public function scheduleSelectBySeq($seq)
    {
        //$query = $this->db->get_where('calendar', array('seq' => $seq));

        $sql = "SELECT seq as seq,
                        content as content,
                        DATE_FORMAT(start_date,'%Y-%c-%e') as start_date,
                        DATE_FORMAT(end_date,'%Y-%c-%e') as end_date
                FROM calendar
                where seq = ?";

        $query = $this->db->query($sql, array($seq));


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