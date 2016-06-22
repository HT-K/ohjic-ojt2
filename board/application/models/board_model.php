<?php

/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-16
 * Time: 오후 1:26
 */
class Board_model extends CI_Model
{
    function __construct() { // 생성자다, 초기화와 관련된 함수다.
        parent::__construct();
    }

    public function listAll()
    {
        /* board 테이블에서 게시글들을 얻어와보자 */
        return $this->db->query('SELECT * FROM board')->result();
    }

    public function listArticle($startRow, $articleSize)
    {
        ///* board 테이블에서 게시글들을 얻어와보자 */
        //return $this->db->query('SELECT * FROM board')->result();

        /* board 테이블에서 개수(articleSize) , 시작위치 게시글(startRow) */
        //return $this->db->get('board', $articleSize, $startRow)->result();
        $this->db->limit($articleSize, $startRow);

        $query = $this->db->get('board');

        return $query->result();
        //return $this->db->query('SELECT * FROM board LIMIT 0, 3')->result();

    }

    /* 모든 게시글 수 리턴 */
    public function listCount()
    {
        /* board 테이블의 row를 카운트해서 리턴해준다. */
        return $this->db->count_all('board');
    }

    /* 디비에 글 insert! */
    public function write($title, $writerName, $password, $content)
    {
        /* reg_date 컬럼의 값은 항상 'NOW()', 즉 현재 시간이 들어가는 것을 디폴트로 한다! */
        $this->db->set('reg_date', 'NOW()', false);

        /* 컬럼 값을 설정(set)해둔다 */
        $this->db->set(array(
            'title'=>$title,
            'writer_name'=>$writerName,
            'password'=>$password,
            'content'=>$content));

        /* 내 데이터베이스 board 테이블에 form에서 가지고 온 값들을 넣는다. */
        /* $this->db->set으로 설정해둔 컬럼과 값들이 board 테이블에 들어가게 된다. */
        $result = $this->db->insert('board');

        return $result;
    }

    /* 해당 게시글의 상세내용 리턴! */
    public function detail($article_seq)
    {
        /* board 테이블에서 게시글 번호와 같은 데이터베이스 1줄(1개의 row())의 데이터를 가져와서 리턴한다. */
        return $this->db->get_where('board', array('seq'=>$article_seq))->row();
    }

    /* 검색어와 일치하는 게시글 수 리턴 */
    public function searchCount ($keyField, $keyword) {
        $sql = "SELECT COUNT(*)
      FROM board
     WHERE $keyField LIKE CONCAT('%','$keyword','%')";


        /* keyFile 값이 title이면 board 테이블의 title 컬럼에서 keyword(검색어)와 같은 값인 글 들을 검색해서 리턴 */
        $result = $this->db->query($sql)->result();

        $count = 0;
        foreach ($result as $enrty)
        {
            ++$count;
        }

        return $count;
    }

    /* 검색지정, 검색어, 게시글 시작위치, 페이지당 보여질 게시글 수를 이용하여 검색 목록 리턴 */
    public function searchArticle ($keyField, $keyword, $startRow, $articleSize) {
       /* $sql = "SELECT * FROM board
                WHERE $keyField LIKE CONCAT('%','$keyword','%')
                ORDER BY seq
                DESC LIMIT $startRow, $articleSize";

            return $this->db->query($sql)->result();*/

        $this->db->select('*');
        $this->db->from('board');
        $this->db->like($keyField, $keyword);
        $this->db->limit($articleSize, $startRow);

        /* result는 객체배열 형태로 리턴 */
        return $this->db->get()->result();
    }

}