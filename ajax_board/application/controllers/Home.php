<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-16
 * Time: 오후 1:25
 */

class Home extends CI_Controller
{
    function __construct()
    { // 생성자다, 초기화와 관련된 함수다.
        parent::__construct();
        /* 내가 설정한 데이터베이스 라이브러리 설정 */
        $this->load->database();

    }

    public function _header()
    {
        $this->load->view('header');
    }

    public function index()
    {
        /* /index.php/board 로 접속 시 /index.php/board/board/1로 리다이렉트 시킨다. */
        $this->load->helper('url'); /* redirect를 쓰기위한 helper */
        /* config.php 에서 $config['base_url'] = '/'; 설정해줘야 동작한다. */
        redirect('board'); /* /index.php를 쓰지 않아도 된다. */
    }

    public function home()
    {
        $this->_header();
        $this->load->view('content');
        $this->load->view('footer');
    }

}