<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    //Main View
    public function index($page = 0)
    {

        echo "<br>".$page;

        $this->benchmark->mark('code_start');

        $val = null;
        if($this->input->get("search_val") != null){

            $val = $this->input->get("search_val");
            $config['reuse_query_string'] = TRUE;



        }

        //total_row
        $this->load->model("Board_model");
        $row_cnt = $this->Board_model->row_get($val);

        //echo $row_cnt;




        //pagination
        $this->load->library('pagination');

        $config['base_url'] = 'http://test.kr/Board/index';
        $config['total_rows'] = $row_cnt;
        $config['per_page'] = 5;


        $config['prev_link'] = "<";
        $config['next_link'] = ">";

        $config['last_link'] = "Last";
        $config['first_link'] = "First";

        $config['num_links'] = 1;


        $this->pagination->initialize($config);


        $data['link'] = $this->pagination->create_links();
        $data['page'] = $page;
        $data['val'] = $val; //검색값

        $this->load->view('board_view', $data);

        $this->benchmark->mark('code_end');





    }

    //글쓰기 View
    public function write_view()
    {


        $this->load->view('board_write_view');


    }

    //글읽기 View
    public function read_view()
    {


        $this->load->view('board_read_view');


    }


    //list get
    public function list_get($search_val=null)
    {

        if($this->input->get("page") != null or $this->input->get("page") != 0 ){

            $page = $this->input->get("page");

        }

        if($this->input->get("search_val") != null){

           $search_val = $this->input->get("search_val");

        }


        //Model
        $this->load->Model("Board_model");
        $obj = $this->Board_model->get_list($page, $search_val);


        echo json_encode($obj, JSON_UNESCAPED_UNICODE);


    }

    //read get
    public function read_get()
    {


        //파라미터 값 get
        $num = $this->input->get("num");


        //model call
        $this->load->model("Board_model");
        $obj = $this->Board_model->read($num);

        echo json_encode($obj, JSON_UNESCAPED_UNICODE);

    }


    //write
    public function write()
    {

        $name = $this->input->get("name");
        $title = $this->input->get("title");
        $contents = $this->input->get("contents");


        //model
        $this->load->model("Board_model");
        $this->Board_model->write($name, $title, $contents);

        $this->load->helper("url");
        //redirect
        redirect("http://test.kr");  //URL 헬퍼를 로드해야함.


    }


}
