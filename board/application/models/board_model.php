<?php

/**
 * Created by PhpStorm.
 * User: ohjic
 * Date: 2016-06-16
 * Time: ���� 1:26
 */
class Board_model extends CI_Model
{
    function __construct() { // �����ڴ�, �ʱ�ȭ�� ���õ� �Լ���.
        parent::__construct();
    }

    public function listAll()
    {
        /* board ���̺��� �Խñ۵��� ���ͺ��� */
        return $this->db->query('SELECT * FROM board')->result();
    }

    public function listArticle($startRow, $articleSize)
    {
        ///* board ���̺��� �Խñ۵��� ���ͺ��� */
        //return $this->db->query('SELECT * FROM board')->result();

        /* board ���̺��� ����(articleSize) , ������ġ �Խñ�(startRow) */
        return $this->db->get('board', $articleSize, $startRow)->result();

        //return $this->db->query('SELECT * FROM board LIMIT 0, 3')->result();

    }

    public function listCount()
    {
        /* board ���̺��� row�� ī��Ʈ�ؼ� �������ش�. */
        return $this->db->count_all('board');
    }

    public function write($title, $writerName, $password, $content)
    {
        /* �� �����ͺ��̽� board ���̺� form���� ������ �� ������ �ִ´�. */
       $result = $this->db->insert('board', array(
            'title'=>$title,
            'writer_name'=>$writerName,
            'password'=>$password,
            'content'=>$content));

        return $result;
    }

    public  function detail($article_seq)
    {
        /* board ���̺��� �Խñ� ��ȣ�� ���� �����ͺ��̽� 1��(1���� row())�� �����͸� �����ͼ� �����Ѵ�. */
        return $this->db->get_where('board', array('seq'=>$article_seq))->row();
    }
}