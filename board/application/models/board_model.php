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
        return $this->db->get('board', $articleSize, $startRow)->result();

        //return $this->db->query('SELECT * FROM board LIMIT 0, 3')->result();

    }

    public function listCount()
    {
        /* board 테이블의 row를 카운트해서 리턴해준다. */
        return $this->db->count_all('board');
    }

    public function write($title, $writerName, $password, $content)
    {
        /* reg_date 컬럼의 값은 항상 'NOW()', 즉 현재 시간이 들어가는 것을 디폴트로 한다! */
        $this->db->set('reg_date', 'NOW()', false);

        /* 내 데이터베이스 board 테이블에 form에서 가지고 온 값들을 넣는다. */
        $result = $this->db->insert('board', array(
            'title'=>$title,
            'writer_name'=>$writerName,
            'password'=>$password,
            'content'=>$content));

        return $result;
    }

    public  function detail($article_seq)
    {
        /* board 테이블에서 게시글 번호와 같은 데이터베이스 1줄(1개의 row())의 데이터를 가져와서 리턴한다. */
        return $this->db->get_where('board', array('seq'=>$article_seq))->row();
    }
}