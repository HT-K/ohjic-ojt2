<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-17
 * Time: 오후 4:13
 */

class Member extends CI_Controller
{
    function __construct()
    { // 생성자다, 초기화와 관련된 함수다.
        parent::__construct();
        /* 내가 설정한 데이터베이스 라이브러리 설정 */
        $this->load->database();
        /* 접근할 모델 설정 */
    }

    public function _head() {
        /* application -> views -> header.php를 로드 */
        $this->load->view('header');
    }

    function login()
    {
        $this->_head();
        $this->load->view('login_form');
        $this->load->view('footer');
    }
}