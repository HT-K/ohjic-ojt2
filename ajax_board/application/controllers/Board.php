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
        $this->load->model('BoardModel');
    }

    public function _header()
    {
        $this->load->view('header');
    }

    public function index()
    {
        $this->_header();
        //가져온 게시글을 board(view)에 보낸다.
        $this->load->view('content3');
        $this->load->view('footer');
    }

    /* ajax와 통신하는 컨트롤러 메소드 */
    public function board()
    {
        $nowPage = $this->input->get('nowPage');
        /* get방식으로 가져온 값은 nowPage 라는 값이 없으면 FALSE를 리턴한다. */
        if ($nowPage == FALSE)
        {
            /* 페이지 정보가 안넘어 오면 디폴트 값으로 1을 설정 */
            $nowPage = 1;
        }

        $keyField = $this->input->get('keyField');
        $keyWord = $this->input->get('keyWord');

        /* 맨 처음 페이지 진입 시 keyFiled와 keyWord 값은 없다 이 때 $this->input->get() 의 리턴 값이 FALSE다 */
        /* 그 다음 검색 버튼을 누르지 않으면 keyWord 값은 문자열 'null' 이다! */
        if ($keyWord == FALSE || $keyWord == 'null')
        {
            /* board 의 모든 게시글 수를 가져온다 */
            $totalArticle = $this->BoardModel->countAll();
        }
        else
        {
            /* 검색어가 존재하면 검색목록과 검색어에 맞는 리스트의 수를 가져온다. */
            $totalArticle = $this->BoardModel->countBySearch($keyField, $keyWord);
        }

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

        /* 현재 페이지에서 보여질 마지막 페이지 숫자 (시작이 1페이지라면 끝은 3페이지) */
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
            'endPage' => $endPage,
            'keyField' => $keyField,
            'keyWord' => $keyWord);

        /* 페이지 관련 정보를 param에 'page'로 저장 */
        $this->param['page'] = $page;

        /* 페이지 정보를 구하고 나서 게시글 내용 가져오기 */
        /* 검색어가 FALSE면 모든 게시글 아니면 검색한 내용의 글*/
        if ($keyWord == FALSE || $keyWord == 'null')
        {
            /* board 의 모든 게시글 수를 가져온다 */
            $articleList = $this->BoardModel->getListAll($startRow, $articleSize);
        }
        else
        {
            $articleList = $this->BoardModel->getListBySearch($keyField, $keyWord, $startRow, $articleSize);
        }

        /* 검색해서 가져온 결과 리스트(객체)를 param에 저장 */
        $this->param['board'] = $articleList;

        /* json으로 리턴 시키기 위해 배열에 저장 */
        $result = array(
            'page' => $page,
            'board' => $articleList
        );

        /* json 으로 encode 하여 .ajax에게 리턴~ (echo를 써줘야한다!) */
        echo json_encode($result, JSON_UNESCAPED_UNICODE);

    }
}