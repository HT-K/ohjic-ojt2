<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ajax board</title>


    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>


    <!-- 합쳐지고 최소화된 최신 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- 부가적인 테마 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    <!-- 합쳐지고 최소화된 최신 자바스크립트 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>


    <script>

        <?php  if($this->input->get("search_val") == null){ ?>

        var search_val = null;

        <?php  }
        else{  ?>

        var search_val = <?php echo '"'.$this->input->get("search_val").'"'; ?>;

        <?php }?>


        $(document).ready(function () {


            if (search_val == null || search_val == "") {

                var page = <?php echo $page;?>;


                var param = {"page": page};

                //alert(page);

                //Board List Load
                $.ajax({


                    url: "http://test.kr/Board/list_get",
                    type: "GET",
                    data: param,
                    dataType: "json",
                    success: function (data) {



                        //alert(data[0].name);

                        var contents = document.getElementById("table_contents").innerHTML;


                        //page당 5개씩 출력
                        for (var i = 0; i < 5; i++) {

                            var row = contents;

                            row = row.replace("[num]", data[i].num);
                            row = row.replace("[num_para]", data[i].num);
                            row = row.replace("[title]", data[i].title);
                            row = row.replace("[name]", data[i].name);


                            $("#board_table_body").append(row);


                        }


                    },
                    error: function (request, status, error) {

                        alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);


                    }


                });


            } //if()

            //검색값이 있을때, 밑에 ajax로 실행.
            else {


                var page = <?php echo $page;?>;


                var param = {"page": page, "search_val": search_val};
                //alert("else : " + search_val);
                //alert(page);

                //alert(page);

                //Board List Load
                $.ajax({


                    url: "http://test.kr/Board/list_get",
                    type: "GET",
                    data: param,
                    dataType: "json",
                    success: function (data) {

                        //alert(data[0].name);

                        var contents = document.getElementById("table_contents").innerHTML;


                        //page당 5개씩 출력
                        for (var i = 0; i < 5; i++) {

                            var row = contents;

                            row = row.replace("[num]", data[i].num);
                            row = row.replace("[num_para]", data[i].num);
                            row = row.replace("[title]", data[i].title);
                            row = row.replace("[name]", data[i].name);


                            $("#board_table_body").append(row);


                        }


                    },
                    error: function (request, status, error) {

                        alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);


                    }


                });


            } //else()


        }); //ready()


        function search() {

            //search_val
            //alert($("#search_val").val());

            var val = $("#search_val").val();

            location.href = "http://test.kr?search_val=" + val;


        }


        function enter(e) {

            var key = e.keyCode;

            if (key == 13) {  /* 아스키 코드.. 13 = enter 값 */

                var val = $("#search_val").val();

                //alert(val);

                location.href = "http://test.kr?search_val=" + val;


            } else {


                return false;

            }

        }


    </script>


    <script id="table_contents" type="text/html">

        <tr>
            <td>[num]</td>
            <td><a href="http://test.kr/Board/read_view?num=[num_para]">[title]</a></td>
            <td>[name]</td>
        </tr>

    </script>


</head>
<body>

<div id="container">

    <div id="header" style="width: 100%;height: 30px;background: #888888;line-height: 30px;text-align: left">Ajax 게시판
    </div>

    <div id="board_area" style="width: 640px; height: 250px;margin: auto;background: #fdf3f2">

        <div id="board_con" style="height: 250px;">
            <table id="board_table" class="table table-condensed">

                <thead>
                <tr>
                    <th>글 번호</th>
                    <th>제 목</th>
                    <th>작성자</th>
                </tr>
                </thead>

                <tbody id="board_table_body">


                </tbody>

            </table>


        </div>

        <div style="height: 50px;">
            <div id="left"
                 style="margin: auto;width: 320px;float: left;border: 1px dotted;height: 100%"> <?php echo $link; ?></div>
            <div id="right" style="float: right;margin: auto;width: 320px;height: 100%; text-align: right">

                <div>
                    <a href="http://test.kr/Board/write_view">
                        <button type="button" class="btn btn-default btn-info">Write</button>
                    </a>

                </div>

                <div>


                        <input type="text" required="required" id="search_val" name="search_val" onkeypress="enter(event)">
                        <button type="button" class="btn btn-default btn-success" onclick="search()">Search(title)
                        </button>



                </div>


            </div>

        </div>


    </div>

    <div>
        <span
            style="font-size: 75%; font-weight: bold; border: 1px solid"><?php echo $this->benchmark->elapsed_time('code_start', 'code_end') . " sec"; ?></span>
        <br>
        <span
            style="font-size: 75%; font-weight: bold; border: 1px solid"><?php echo $this->benchmark->memory_usage(); ?></span>

    </div>


</div>

</body>
</html>