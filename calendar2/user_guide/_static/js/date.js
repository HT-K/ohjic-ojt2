var dateObj = (function (){

    return {
        getMonthStart : function(year, month){ // 해당 '월'의 시작 '일'(1일)을 구한다.
            var date = new Date();
            date.setDate(year, month, 1); // 현재 시간을 현재 '월'의 1일로 세팅

            var monthStartSunDay = date.setDate(date.getDate() - date.getDay()); // 현재 '월'의 1일 - 현재 '월'의 시작요일 == 현재 '월'의 첫 주 일요일
            return monthStartSunDay; //
        }, // getMonthStart End
    }

})(); // dateObj 모듈 End

var dateDraw = (function () {

    var monthView = function (year, month, num) { // 현재 '월'을 구하는 함수
        month = month + num; // 현재 '월' 인지, 이전 '월' 인지, 다음 '월' 인지 구분한다. (0, -1, 1 값이 num 값으로 온다)

        if (month == -1) { // 현재 year(연도)에서 더이상 이전 클릭 할 '월'이 존재하지 않으면 12월을 뜻하는 11로 바꿔준다.
            year = year - 1; // 1년 감소
            month = 11;
        } else if (month == 12) { // 현재 year(연도)에서 더이상 다음 클릭 할 '월'이 존재하지 않으면 1월을 뜻하는 0으로 바꿔준다.
            year = year + 1; // 1년 증가
            month = 0;
        }

        var date = dateObj.getMonthStart(year, month); // 현재 '월'의 첫 주차 일요일로 세팅된 date 값을 가져온다..

        var view = '<tr>';
        // 7 * 6으로 고정된 달력을 만들도록해보자.
        for (var i = 1; i <=6; i++) {
            for (var j = 1; j <= 7; j++) {
                var temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            }
        }
    };

    var weekView = function (date, week) {
        var weekStart = date.setDate(date.getDate() + week * 7); // 주간의 시작 '일요일'로 세팅! (week 값은 0, -1, 1 값으로 현재, 이전 다음 주의 일요일 세팅을 하기 위한 값이다.)
    };

    return {
        monthView : function (year, month, num) { // '월' 달력을 그려주기 위한 함수

            mouseEvent.init(); // tbody에 마우스 이벤트 연결
        }, // monthView End

        weekView : function(date, week) { // '주' 달력을 그려주기 위한 함수
            weekView(date, week);
        }, //weekView End
    }

})(); // dateDraw 모듈 End

var mouseEvent = (function () {
    var dragStart = 0;
    var dragEnd = 0;
    var isDragging = false;
    var strDate;
    var endDate;

    function rangeMouseDown (e) {
        if (isRightClick(e)) {
            return false;
        } else {
            var allCells = $("#calendar_body tr td");
            dragStart = allCells.index($(this));
            isDragging = true;

            strDate = e.target.textContent; // 마우스 드래그 시작 날짜 가져오기!!

            if (typeof e.preventDefault != 'undefined') { e.preventDefault(); }
            document.documentElement.onselectstart = function () { return false; };
        }
    }

    function rangeMouseUp(e) {
        if (isRightClick(e)) {
            return false;
        } else {
            var allCells = $("#calendar_body tr td");
            dragEnd = allCells.index($(this));

            endDate = e.target.textContent; // 마우스 드래그 제일 끝 날짜 가져오기!!
            $("#scheduleStart").html(strDate); // 해당 기간을 p 태그에 출력!
            $("#scheduleEnd").html(endDate); // 해당 기간을 p 태그에 출력!
            $("#scheduleModal").modal('show'); // show 모달!

            isDragging = false;
            if (dragEnd != 0) {
                selectRange();
            }

            document.documentElement.onselectstart = function () { return true; };
        }
    }

    function rangeMouseMove(e) {
        if (isDragging) {
            var allCells = $("#calendar_body tr td");
            dragEnd = allCells.index($(this));
            selectRange();
        }
    }

    function selectRange() {
        $("#calendar_body tr td").removeClass('selected');
        if (dragEnd + 1 < dragStart) { // reverse select
            $("#calendar_body tr td").slice(dragEnd, dragStart + 1).addClass('selected');
        } else {
            $("#calendar_body tr td").slice(dragStart, dragEnd + 1).addClass('selected');
        }
    }

    function isRightClick(e) {
        if (e.which) {
            return (e.which == 3);
        } else if (e.button) {
            return (e.button == 2);
        }
        return false;
    }

    var scheduleReg = function () {
        var data = {
            content : $("#scheduleContent").val(),
            strDate : strDate,
            endDate : endDate
        };
        $.ajax({
            url : 'http://calendar.kr/calendar/scheduleSet', // 호출할 컨트롤러의 메소드
            data : data,
            type : 'post',
            dataType : 'json',
            success : function(data) {
                alert(data.check);
                $("#scheduleModal").modal('hide'); // 일정 등록이 끝났으면 hide!
                $("#scheduleContent").val(""); // 해당 텍스트 박스 값 바꾸기
            },
            error : function() {
                alert("error");
            }
        });
    };

    return { // 아래 init과 scheduleInsert는 어디서든지 접근할 수 있는 publick이 됨
        init: function () { // monthView와 weekView에서 호출해야되므로 public으로!
            $("#calendar_body tr td")
                .mousedown(rangeMouseDown)
                .mouseup(rangeMouseUp)
                .mousemove(rangeMouseMove);
        },

        scheduleInsert : function () { // '일정등록' 버튼 클릭 시 호출되야해서 publick으로 해주고 내부 함수인 scheduleReg()를 호출되게 한다.
            scheduleReg();
        }
    };

})(); // mouseEvent 모듈 End
