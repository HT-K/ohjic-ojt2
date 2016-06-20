<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-16
 * Time: 오후 1:25
 */

class Board extends CI_Controller
{
    function __construct()
    { // 생성자다, 초기화와 관련된 함수다.
        parent::__construct();
        /* 내가 설정한 데이터베이스 라이브러리 설정 */
        $this->load->database();
        /* 접근할 모델 설정 */
        $this->load->model('board_model');
    }

    public function _head() {
        /* application -> views -> header.php를 로드 */
        $this->load->view('header');
    }

    public  function index()
    {
        /* /index.php/board 로 접속 시 /index.php/board/board/1로 리다이렉트 시킨다. */
        $this->load->helper('url'); /* redirect를 쓰기위한 helper */
        /* config.php 에서 $config['base_url'] = '/'; 설정해줘야 동작한다. */
        redirect('/board/board/1'); /* /index.php를 쓰지 않아도 된다. */
    }

    /* board.kr/index.php/board/board/1 ===> 이거로 접속 시 호출되는 메소드 */
    public function board($nowPage)
    {
        ///* board_model에 접근해서 쿼리 실행 후 모든 게시글을 가져온다. */
        //$board_all = $this->board_model->listAll();

        /* board 의 모든 게시글 수를 가져온다 */
        $totalArticle = $this->board_model->listCount();

        /* 한 페이지 당 보여줄 게시글 수 */
        $articleSize = 3;

        /* 총 게시물 수에 비례한 총 페이지 수 */
        $nmg = $totalArticle % $articleSize;
        if ($nmg != 0) {
            /* round 함수는 결과 값이 소수일 경우 반올림해주는 메소드다 */
            $totalPage = ceil(($totalArticle / $articleSize)+1);
        } else {
            $totalPage = ceil($totalArticle / $articleSize);
        }

        /* 한 페이지에 보여줄 최대 페이지 수 */
        $pageSize = 3;

        /* 디비에서 몇번째 게시물 부터 꺼내와야 할지 결정하는 변수 */
        /* (현재페이지 - 1) * articleSize 를 하면 시작 게시물 위치를 알 수 있다.  */
        $startRow = ($nowPage - 1) * $articleSize;

        //페이지 끝 구하는 공식, ex) prePage(현재페이지 번호)가 3이고 pa
        //geSize가 5이면 3*5 = 15
        //즉, 3페이지에 맨 끝 글은 15번째 글이 된다는 뜻으로 startRow와 endRow를 합치면 11~15번째 게시글이 3페이지에 보이게 된다!
        //그러나 만약 글이 11개라면 마지막 페이지의 endRow는 11이 되어야한다. 즉 ,pageNo * pageSize 가 totalArticle(총 글 수)보다 크면 endRow를 총 글 수로 지정해줘야 마지막에 해당 수만큼 글이 해당 페이지에 들어간다!
        if ($nowPage * $articleSize > $totalArticle) {
            $endRow = $totalArticle;
        } else {
            $endRow = $nowPage * $articleSize;
        }

        /* 현재 페이지에 보여지는 시작 페이지 숫자 (1페이지면 1부터 보여진다) */
        $startPage = $nowPage - (($nowPage-1) % $pageSize);

        /* 현재 페이지에서 보여질 마지막 페이지 숫자 (시작이 1페이지라면 끝은 4페이지) */
        $end = $startPage + $pageSize - 1;
        if ($end > $totalPage) {
            $end = $totalPage;
        }
        $endPage = $end;

        /* 페이징과 관련된 정보를 array에 담는다. */
        /* controller에서 param에 array 형태로 저장해서 뷰에 보낼경우
           $page['nowPage'] 와 같은 형태로 받아야한다. */
        $page = array(
            'nowPage' => $nowPage,
            'totalArticle' => $totalArticle,
            'articleSize' => $articleSize,
            'totalPage' => $totalPage,
            'pageSize' => $pageSize,
            'startRow' => $startRow,
            'endRow' => $endRow,
            'startPage' => $startPage,
            'endPage' => $endPage);

        /* 페이지 관련 정보를 param에 'page'로 저장 */
        $this->param['page'] = $page;

        /* startRow와 articleSize(보여줄 게시글 수) 를 이용하여 디비에서 게시글 가져오기 */
        $articleList = $this->board_model->listArticle($startRow, $articleSize);

        /* 검색해서 가져온 결과 리스트를 param에 저장 */
        $this->param['board'] = $articleList;

        $this->_head();
        /* 가져온 게시글을 board(view)에 보낸다. */
        $this->load->view('board', $this->param);
        $this->load->view('footer');
    }

    /* board.kr/index.php/board/writeForm ===> 이거로 접속 시 호출되는 메소드 */
    /* 글쓰기 폼으로 고고 */
    public function writeForm()
    {
        $this->_head();
        $this->load->view('writeForm');
        $this->load->view('footer');
    }

    /* writeForm 에서 글 등록을 눌렀을 시 호출되는 메소드 */
    public function write()
    {
        /* writeForm 에서 받아온 값들을 각각의 변수에 저장한다. */
        $title = $this->input->post('title');
        $writerName = $this->input->post('writerName');
        $password = $this->input->post('password');
        $content = $this->input->post('content');

        /* 받아온 값을 model의 write()에 넘기자 */
        $result = $this->board_model->write($title, $writerName, $password, $content);

        /* 글 등록 후에는 다시 게시판 홈으로 가자 */
        $this->load->helper('url'); /* redirect를 쓰기위한 helper */
        /* config.php 에서 $config['base_url'] = '/'; 설정해줘야 동작한다. */
        redirect('/board/board/1' , 'refresh'); /* /index.php를 쓰지 않아도 된다. */

    }

    /* board.kr/index.php/board/detail ===> 이거로 접속 시 호출되는 메소드 */
    public function detail($article_seq)
    {
        /* 게시글 번호를 가져와서 해당 게시글에 대한 정보를 데이터베이스에서 가져온다. */
        $article_row = $this->board_model->detail($article_seq);

        $this->_head();
        /* 가져온 1줄의 데이터를 detailForm에 보내서 뿌린다 */
        /* row로 가져온 데이터이므로 뷰에서 for 문을 돌릴 필요가 없다. */
        /* 뷰에서 그냥 article->title 처럼 바로 접근해도 된다. */
        $this->load->view('detailForm', array('article'=>$article_row));
        $this->load->view('footer');
    }

    /* 검색 버튼 클릭 시 호출되는 메소드 */
    public function search($nowPage) {
        /* view의 form에서 보내온 keyFiled와 keyword 값 받아오기 */
        //$keyField = $_GET['keyField'];
        //$keyword = $_GET['keyword'];

        /* 이 방법으로도 가능하다. */
        $keyField = $this->input->get('keyField');
        $keyword = $this->input->get('keyword');

        /* board 의 모든 게시글 수를 가져온다 */
        $totalArticle = $this->board_model->searchCount($keyField, $keyword);

        /* 한 페이지 당 보여줄 게시글 수 */
        $articleSize = 3;

        /* 총 게시물 수에 비례한 총 페이지 수 */
        $nmg = $totalArticle % $articleSize;
        if ($nmg != 0) {
            /* ceil 함수는 결과 값이 소수일 경우 올림해주는 메소드다 */
            $totalPage = ceil(($totalArticle / $articleSize)+1);

        } else {
            $totalPage = ceil($totalArticle / $articleSize);
        }

        /* 한 페이지에 보여줄 최대 페이지 수 */
        $pageSize = 3;

        /* 디비에서 몇번째 게시물 부터 꺼내와야 할지 결정하는 변수 */
        /* (현재페이지 - 1) * articleSize 를 하면 시작 게시물 위치를 알 수 있다.  */
        $startRow = ($nowPage - 1) * $articleSize;

        //페이지 끝 구하는 공식, ex) prePage(현재페이지 번호)가 3이고 pa
        //geSize가 5이면 3*5 = 15
        //즉, 3페이지에 맨 끝 글은 15번째 글이 된다는 뜻으로 startRow와 endRow를 합치면 11~15번째 게시글이 3페이지에 보이게 된다!
        //그러나 만약 글이 11개라면 마지막 페이지의 endRow는 11이 되어야한다. 즉 ,pageNo * pageSize 가 totalArticle(총 글 수)보다 크면 endRow를 총 글 수로 지정해줘야 마지막에 해당 수만큼 글이 해당 페이지에 들어간다!
        if ($nowPage * $articleSize > $totalArticle) {
            $endRow = $totalArticle;
        } else {
            $endRow = $nowPage * $articleSize;
        }

        /* 현재 페이지에 보여지는 시작 페이지 숫자 (1페이지면 1부터 보여진다) */
        $startPage = $nowPage - (($nowPage-1) % $pageSize);

        /* 현재 페이지에서 보여질 마지막 페이지 숫자 (시작이 1페이지라면 끝은 4페이지) */
        $end = $startPage + $pageSize - 1;
        if ($end > $totalPage) {
            $end = $totalPage;
        }
        $endPage = $end;

        /* 페이징과 관련된 정보를 array에 담는다. */
        /* controller에서 param에 array 형태로 저장해서 뷰에 보낼경우
           $page['nowPage'] 와 같은 형태로 받아야한다. */
        $page = array(
            'nowPage' => $nowPage,
            'totalArticle' => $totalArticle,
            'articleSize' => $articleSize,
            'totalPage' => $totalPage,
            'pageSize' => $pageSize,
            'startRow' => $startRow,
            'endRow' => $endRow,
            'startPage' => $startPage,
            'endPage' => $endPage);

        /* 페이지 관련 정보를 param에 'page'로 저장 */
        $this->param['page'] = $page;

        /* startRow와 articleSize(보여줄 게시글 수) 과 검색할 것, 검색내용 을 이용해서 해당 게시글들 가져오기 */
        $articleList = $this->board_model->searchArticle($keyField, $keyword, $startRow, $articleSize);

        /* 검색해서 가져온 결과 리스트를 param에 저장 */
        $this->param['board'] = $articleList;

        $this->_head();
        /* 가져온 게시글을 board(view)에 보낸다. */
        $this->load->view('search', $this->param);
        $this->load->view('footer');
    }

}