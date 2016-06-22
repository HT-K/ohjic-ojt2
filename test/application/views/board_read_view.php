<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
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


    <script type="text/javascript">

        $(document).ready(function () {


            var num = <?php echo $this->input->get("num"); ?>

                //alert(num);

            var param = {"num": num};

            $.ajax({


                url: "http://test.kr/Board/read_get",
                data: param,
                type: "GET",
                dataType: "json",
                success: function (data) {

                   // alert(data.name);

                    $("#name").val(data.name);
                    $("#title").val(data.title);
                    $("#contents").val(data.contents);

                }


            });


        });


    </script>


</head>
<body>

<div id="container">

    <div id="header" style="width: 100%;height: 30px;background: #888888;line-height: 30px;text-align: left">글 작성
    </div>

    <div id="board_area" style="width: 640px; height: 500px;margin: auto;background: #fdf3f2">

        <div id="board_write_form" style="height: 450px; border: 1px dotted">

            <form role="form" action="#" method="get">
                <div class="form-group">
                    <label>작성자 : </label>
                    <input type="text" class="form-control" id="name" name="name" readonly="readonly">
                </div>
                <div class="form-group">
                    <label>제 목 : </label>
                    <input type="text" class="form-control" id="title" name="title" readonly="readonly">
                </div>
                <div class="form-group">
                    <label>내 용 : </label>
                    <textarea id="contents" name="contents" cols="70" rows="10" readonly="readonly"></textarea>
                </div>


            </form>


        </div>

        <div>

            <a href="javascript:history.back();">뒤로가기</a>


        </div>




    </div>

    <div>

        <span
            style="font-size: 75%; font-weight: bold; border: 1px solid"><?php echo $this->benchmark->memory_usage(); ?></span>

    </div>


</div>

</body>
</html>