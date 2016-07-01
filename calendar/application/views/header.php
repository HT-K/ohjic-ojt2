<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>캘린더 만들기</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- 합쳐지고 최소화된 최신 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- 부가적인 테마 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    <!-- 합쳐지고 최소화된 최신 자바스크립트 -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <!--<script src = "../user_guide/_static/js/jquery-3.0.0.min.js"></script>-->

    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <link rel="stylesheet" href="../user_guide/_static/css/calendar.css">
</head>
<body>
<div id="wrap">
    <div style="margin-top: 30px; height: 5px; background-color: #d9d9d9;"></div>
    <form style="margin-top: 10px; ">
        <button id="today" style="float: left; width: 50px; height: 30px; margin-right: 10px;">오늘</button>
        <button id="prev" style="float: left; width: 35px; height: 30px;"><</button>
        <button id="next" style="float: left; width: 35px; height: 30px;">></button>
        <div id="ym" style="float: left; width: 270px; height: 30px; text-align: center; margin-top: 5px;">

        </div>

        <button id="month" style="float: right; width: 100px; height: 30px; background-color: #409ad5; margin-left: 10px;">월</button>
        <button id="week" style="float: right; width: 100px; height: 30px; background-color: #409ad5;">주</button>
    </form>
    <div style="margin-top: 50px; height: 5px; background-color: #d9d9d9;"></div>

