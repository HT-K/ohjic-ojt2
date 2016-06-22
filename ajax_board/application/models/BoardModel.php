<?php

/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-16
 * Time: 오후 1:26
 */
class BoardModel extends CI_Model
{
    function __construct()
    { // 생성자다, 초기화와 관련된 함수다.
        parent::__construct();
    }

    /* 모든 게시글 수 리턴 */
    public function countAll()
    {
        /* board 테이블의 row를 카운트해서 리턴해준다. */
        return $this->db->count_all('board');
    }

    /* 모든 게시글 내용 리턴 */
    public function getListAll($startRow, $articleSize)
    {
        /* board 테이블에서 개수(articleSize) , 시작위치 게시글(startRow) */
        //return $this->db->get('board', $articleSize, $startRow)->result();
        $this->db->limit($articleSize, $startRow);

        $query = $this->db->get('board');

        return $query->result();
        //return $this->db->query('SELECT * FROM board LIMIT 0, 3')->result();
    }

    public function countBySearch($keyField, $keyWord)
    {
        $sql = "SELECT COUNT(*) as num
                FROM board
                WHERE $keyField LIKE CONCAT('%','$keyWord','%')";


        /* keyFile 값이 title이면 board 테이블의 title 컬럼에서 keyword(검색어)와 같은 값인 글 들을 검색해서 리턴 */
        $result = $this->db->query($sql)->row_array();

        /*print_r($result['num']); 구글 개발자모드의 Network-> Preview에서 값을 확인해볼 수 있다.*/

        return $result['num'];

    }

    public function getListBySearch($keyField, $keyWord, $startRow, $articleSize)
    {
     /*$sql = "SELECT * FROM board
            WHERE $keyField LIKE CONCAT('%','$keyword','%')
            ORDER BY seq
            DESC LIMIT $startRow, $articleSize";

        return $this->db->query($sql)->result();*/

        $this->db->select('*');
        $this->db->from('board');
        $this->db->like($keyField, $keyWord);
        $this->db->limit($articleSize, $startRow);

        return $this->db->get()->result();
    }
}

