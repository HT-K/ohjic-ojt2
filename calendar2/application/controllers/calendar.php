<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-16
 * Time: 오후 1:25
 */

class Calendar extends CI_Controller
{
    function __construct()
    { // 생성자다, 초기화와 관련된 함수다.
        parent::__construct();
        /* 내가 설정한 데이터베이스 라이브러리 설정 */
        $this->load->database();
        /* 접근할 모델 설정 */
        $this->load->model('CalendarModel');
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('content');
        $this->load->view('footer');
    }

    public function scheduleGet()
    {
        $startDate = $this->input->post('strDate');
        $result = $this->CalendarModel->scheduleSelect($startDate);
        $ret = array (
            'cal' => $result
        );

        echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    }


    public function scheduleSet()
    {
        /* post로 보낸 값을 받는다. */
        $content = $this->input->post('content');
        $startDate = $this->input->post('strDate');
        $endDate = $this->input->post('endDate');

        /* 데이터베이스와 통신하기 위해 모델을 호출 */
        $this->CalendarModel->scheduleInsert($content, $startDate, $endDate);

        $check = "성공";

        $result = array(
            'check' => $check
        );

        /* json 으로 encode 하여 .ajax에게 리턴~ (echo를 써줘야한다!) */
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

}