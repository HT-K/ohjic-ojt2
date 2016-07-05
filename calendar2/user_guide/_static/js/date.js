var dateObj = (function (){

    return {
        getMonthStart : function(year, month){ // 해당 '월'의 시작 '일'(1일)을 구한다.
            var date = new Date(year, month, 1);
            date.setDate(date.getDate() - date.getDay()); // 현재 '월'의 1일 - 현재 '월'의 시작요일 == 현재 '월'의 첫 주 일요일
            return date;
        }, // getMonthStart End
    }

})(); // dateObj 모듈 End

var dateDraw = (function () {

    var monthView = function (year, month) { // 현재 '월'을 구하는 함수
        var today = new Date();
        today = today.getFullYear()+ "-" + (today.getMonth()+1) + "-" + today.getDate(); // 현재 날짜에 배경색을 칠하기 위해 미리 구해놓음.
        var date = dateObj.getMonthStart(year, month); // 현재 '월'의 첫 주차 일요일로 세팅된 date 값을 가져온다.
        var ym = date.getFullYear() + "년 " + (date.getMonth() + 1) + "월 " + date.getDate() + "일 ~ "; // 주간 시작 날짜
        var tempDay = date.getDay(); // 현재 요일, 1일씩 증가시킬 것이다 (토요일과 일요일 색을 달리하기 위함)

        var view = '<tr>'; // 한 주에 하나씩
        // 7 * 6으로 고정된 달력을 만들도록해보자.
        for (var i = 1; i <=6; i++) {
            for (var j = 1; j <= 7; j++) {
                var temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                if (today == temp) { // 오늘 날짜와 td에 들어갈 날짜가 같으면 (즉, 달력에 뿌려질 날짜가 오늘이면)
                    view += '<td style="background-color : gray;" data-cal="'+temp+'">';
                } else {
                    view += '<td data-cal="'+temp+'">';
                }

                if(tempDay == 0) //요일이 일요일 일때 글씨색을 붉은색으로 한다.
                {
                    view += '<font color="red">'+ temp +'</font></td>';
                    tempDay++;
                }
                else if(tempDay == 6) //요일이 토요일 일때 글씨색을 파란색으로 한다.
                {
                    view += '<font color="blue">'+ temp +'</font></td>';
                    tempDay++;
                }
                else //평일 일때 글씨색을 검정색으로 한다.
                {
                    view += temp +'</td>';
                    tempDay++;
                }

                if(tempDay > 6) //테이블의 새로운 행을 추가하도록 한다.
                {
                    tempDay = 0;
                    view += '</tr>';
                    view += '<tr>';
                }

                date.setDate(date.getDate() + 1); // 1일 증가!
            }
        }

        date.setDate(date.getDate() - 1); // 늘어난 1일을 잠시 -1 해서 세팅 (주간 달력에 글씨 띄우기 위함)

        ym += date.getFullYear()+"년 " + (date.getMonth()+1) +"월 " + date.getDate() +"일"; // 주간 끝 날짜

        $("#ym").html(ym);
        $("#calendar_body").html(view);
    }; // monthView() End

    var weekView = function (date, week) {
        var weekStart = date.setDate(date.getDate() + week * 7); // 주간의 시작 '일요일'로 세팅! (week 값은 0, -1, 1 값으로 현재, 이전 다음 주의 일요일 세팅을 하기 위한 값이다.)
        var today = new Date();
        today = today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate(); // 현재 날짜에 배경색을 칠하기 위해 미리 구해놓음.

        var title = date.getFullYear() + "년 " + (date.getMonth() + 1) + "월 " + date.getDate() + "일 ~ "; // 주간 시작 날짜
        var temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(); // 현재 주간의 '일요일' 이다.

        // 세팅된 현재 주간의 일요일 날짜부터 토요일날짜까지 +1 씩 해주면서 td에 세팅한다.
        var view = '<tr>';
        for (var i = 0; i < 7; i++) {
            temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            if (today == temp) // 오늘 날짜와 td에 들어갈 날짜가 같으면 (즉, 달력에 뿌려질 날짜가 오늘이면)
            {
                view += '<td style="background-color: gray;" data-cal="'+temp+'">';
            }
            else
            {
                view += '<td data-cal="'+temp+'">';
            }

            view += temp +'</td>';
            date.setDate(date.getDate() + 1); // 해당 일 기준으로 다음날로 세팅
        }
        view += '</tr>';

        date.setDate(date.getDate() - 1); // 늘어난 1일을 잠시 -1 해서 세팅 (주간 달력에 글씨 띄우기 위함)

        title += date.getFullYear()+"년 " + (date.getMonth()+1) +"월 " + date.getDate() +"일"; // 주간 끝 날짜

        date.setDate(date.getDate() + 1);

        date.setDate(date.getDate() - 7); // 다시 현재 주간의 일요일로 세팅

        $("#ym").html(title);
        $("#calendar_body").html(view);

    }; // weekView() End

    return {
        monthView : function (year, month) { // '월' 달력을 그려주기 위한 함수
            monthView(year, month);
            mouseEvent.init(); // tbody에 마우스 이벤트 연결

        }, // monthView End

        weekView : function(date, week) { // '주' 달력을 그려주기 위한 함수
            weekView(date, week);
            mouseEvent.init(); // tbody에 마우스 이벤트 연결
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
            //console.log($(e.target).data("cal"));
            mouseEvent.setStrDate(e); // 마우스 드래그 시작 날짜 가져오기!!

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
            mouseEvent.setEndDate(e); // 마우스 드래그 제일 끝 날짜 가져오기!!

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

    return { // 아래 init과 scheduleInsert는 어디서든지 접근할 수 있는 publick이 됨
        init: function () { // monthView와 weekView에서 호출해야되므로 public으로!
            $("#calendar_body tr td")
                .mousedown(rangeMouseDown)
                .mouseup(rangeMouseUp)
                .mousemove(rangeMouseMove);
        },

        // 일정 시작일과 끝일을 getter / setter 로 만들어 놓는다.
        setStrDate : function(e) {
            //.log($(e.target).children(".selDate")[0].innerText);
            //strDate = $(e.target).children(".selDate")[0].innerText; // 날짜 값 가져와서 설정!
            strDate = $(e.target).data("cal");
        },

        getStrDate : function() {
            return strDate;
        },

        setEndDate : function(e) {
            //endDate = $(e.target).children(".selDate")[0].innerText;
            endDate = $(e.target).data("cal");
        },

        getEndDate : function() {
            return endDate;
        }
    };

})(); // mouseEvent 모듈 End

var ajaxFunc = (function () {
    var view; // month 혹은 week의 view 내용을 백업하는 변수

    var scheduleInsert = function (flag) {
        var data = {
            content : $("#scheduleContent").val(),
            strDate : mouseEvent.getStrDate(), // mouseEvent에 등록된 일정 시작일과 끝일을 가지고 온다.
            endDate : mouseEvent.getEndDate()
        };
        $.ajax({
            url : 'http://calendar2.kr/calendar/scheduleSet', // 호출할 컨트롤러의 메소드
            data : data,
            type : 'post',
            dataType : 'json',
            success : function(data) {
                alert(data.check);
            },
            error : function() {
                alert("error");
            }
        });
    };

    var weekSchedule = function(today, temp) {

        $.ajax({
            url: 'http://calendar2.kr/calendar/scheduleGet', // 호출할 컨트롤러의 메소드
            data: {strDate: temp},
            type: 'post',
            dataType: 'json',
            async: false, // ajax 실행을 동기적으로 하겠다는 의미 (ajax는 비동기적 호출을 위한 함수로 하나의 작업 실행 후 다른 작업이 자동으로 실행되게 된다)
            success: function (data) {
                var v = ''; // 해당 일(temp)의 td를 만들기 위한 변수

                if (today == temp) // 오늘 날짜와 td에 들어갈 날짜가 같으면 (즉, 달력에 뿌려질 날짜가 오늘이면)
                {
                    v += '<td style="background-color: gray;" data-cal="'+temp+'">';
                }
                else
                {
                    v += '<td data-cal="'+temp+'">';
                }

                if (data.cal.length > 0) { // 일정이 있는 날이면
                    for (var i = 0; i < data.cal.length; i++) {
                        v += '<div class="schIn" style="width: 100%; height:20px; background-color: orange;">'+ data.cal[i].content +'</div>';
                    }
                }
                v += temp +'</td>';
                ajaxFunc.setView(v);
            },
            error: function () {
                alert("error");
            }
        });
    };

    return {
        scheduleInsert : function (flag) { // '일정등록' 버튼 클릭 시 호출되야해서 publick으로 해주고 내부 함수인 scheduleReg()를 호출되게 한다.
            scheduleInsert(flag);
        },

        setView : function (v) {
            view = v;
        },

        getView : function (today, temp) {
            weekSchedule(today, temp);
            return view;
        }
    }

})(); // ajaxFunc 모듈 End
