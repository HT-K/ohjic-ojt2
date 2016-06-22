<?php


class Board_model extends CI_Model
{

    //생성자
    function Board_model()
    {

        parent::__construct();


    }

    public function get_list($page, $search_val)
    {
        //echo $num;
        //$sql = "select * from board_table order by num DESC";
        //$query = $this->db->query($sql);

        if ($search_val == null) {

            $sql = "select * from board_table order by num desc LIMIT " . $page . ", 5";

        } else {

            $sql = "select * from board_table WHERE title='" . $search_val . "' order by num desc LIMIT " . $page . ", 5";

        }


        $query = $this->db->query($sql);


        $row = $query->result();


        return $row;

    }


    //select count
    public function row_get($val)
    {
        //Query String

        //echo "<br>" . $val;

        if ($val == null or $val == "") {

            $sql = "select count(num) as cnt from board_table";

        } else {

            $sql = "select count(num) as cnt from board_table WHERE title='" . $val . "'";

        }

        $query = $this->db->query($sql);

        $row = $query->row();

        $result = $row->cnt;

        return $result;
    }


    public function read($num)
    {

        $sql = "select * from board_table where num=" . $num;
        $query = $this->db->query($sql);

        $res = $query->row();
        //$res = $query->result(); //에러

        return $res;

    }

    public function write($name, $title, $contents)
    {

        $sql = "insert into board_table(title, name, contents) VALUES('" . $title . "','" . $name . "','" . $contents . "')";
        $this->db->query($sql);


    }

}