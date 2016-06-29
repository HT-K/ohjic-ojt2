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
        //$this->load->model('BoardModel');
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('content');
        $this->load->view('footer');
    }
}