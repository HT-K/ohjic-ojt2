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
    var nowDate; // 현재 주간화면을 백업해놓기 위한 변수
    var calendarStart;
    var calendarEnd;
    var backUpYear;
    var backUpMonth;

    var monthView = function (year, month) { // 현재 '월'을 구하는 함수
        backUpYear = year;
        backUpMonth = month;

        var today = new Date();
        today = today.getFullYear()+ "-" + (today.getMonth()+1) + "-" + today.getDate(); // 현재 날짜에 배경색을 칠하기 위해 미리 구해놓음.
        var date = dateObj.getMonthStart(year, month); // 현재 '월'의 첫 주차 일요일로 세팅된 date 값을 가져온다.

        calendarStart = date.getFullYear() + "년 " + (date.getMonth() + 1) + "월 " + date.getDate() + "일 ~ "; // 주간 시작 날짜
        $("#calendarStart").text(calendarStart);
        calendarStart = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
        date.setDate(date.getDate() + 41); // 다시 현재 '달'의 마지막날로 지정
        calendarEnd = date.getFullYear()+"년 " + (date.getMonth()+1) +"월 " + date.getDate() +"일"; // 주간 끝 날짜
        $("#calendarEnd").html(calendarEnd);
        calendarEnd = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
        date.setDate(date.getDate() - 41); // 다시 현재 '달'의 시작일로 지정

        var tempDay = date.getDay(); // 현재 요일, 1일씩 증가시킬 것이다 (토요일과 일요일 색을 달리하기 위함)

        var view = '<tr>'; // 한 주에 하나씩
        view += ajaxFunc.getMonthView(today, tempDay, date);

        $("#calendar_body").html(view);
    }; // monthView() End

    var weekView = function (date, week) {
        dateDraw.backUpWeekData(date); // 현재 주간 백업!
        var weekStart = date.setDate(date.getDate() + week * 7); // 주간의 시작 '일요일'로 세팅! (week 값은 0, -1, 1 값으로 현재, 이전 다음 주의 일요일 세팅을 하기 위한 값이다.)
        var today = new Date();
        today = today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate(); // 현재 날짜에 배경색을 칠하기 위해 미리 구해놓음.

        calendarStart = date.getFullYear() + "년 " + (date.getMonth() + 1) + "월 " + date.getDate() + "일 ~ "; // 주간 시작 날짜
        $("#calendarStart").text(calendarStart);
        calendarStart = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
        date.setDate(date.getDate() + 6); // 다시 현재 주간의 토요일로 세팅
        calendarEnd = date.getFullYear()+"년 " + (date.getMonth()+1) +"월 " + date.getDate() +"일"; // 주간 끝 날짜
        $("#calendarEnd").html(calendarEnd);
        calendarEnd = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
        date.setDate(date.getDate() - 6); // 다시 현재 주간의 일요일로 세팅

        // 세팅된 현재 주간의 일요일 날짜부터 토요일날짜까지 +1 씩 해주면서 td에 세팅한다.
        var view = '<tr>';
        view += ajaxFunc.getWeekView(today, date);
        view += '</tr>';

        date.setDate(date.getDate() - 7); // 다시 현재 주간의 일요일로 세팅, 대체 왜 해줘야하는거지?

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

        backUpWeekData : function(date) {  // 주간 화면을 띄우기 위해 필요한 today, temp 를 백업시켜놓는다.
            nowDate = date;
        },

        getWeekData : function() {
            return nowDate;
        },

        getCalendarStart : function () { // 캘린더 시작 날짜 리턴!
            return calendarStart;
        },

        getCalendarEnd : function () { // 캘린더 끝 날짜 리턴!
            return calendarEnd;
        },

        getBackUpYear : function () { // 월 달력 다시 출력하고자 할 때
            return backUpYear;
        },

        getBackUpMonth : function () { // 월 달력 다시 출력하고자 할 때
            return backUpMonth;
        }
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
            mouseEvent.setEndDate(e); // 마우스 드래그 제일 끝 날짜 가져오기!!
            $("body").mouseup(rangeMouseUp); // td에 마우스 다운 이벤트가 들어오면 body에 마우스 업 이벤트를 연결시킨다!
            

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

            // 거꾸로 드래그할 때를 위해 이 계산이 필요하다.
            var start = new Date(strDate);
            var end = new Date(endDate);

            if (start > end) {
                var temp = strDate;
                strDate = endDate;
                endDate = temp;
            }

            $("#scheduleStart").html(strDate); // 해당 기간을 p 태그에 출력!
            $("#scheduleEnd").html(endDate); // 해당 기간을 p 태그에 출력!
            $("#scheduleModal").modal('show'); // show 모달!
            $("body").off(); // 얘는 th 마우스다운 이벤트 일때만 호출되고 마우스업 후엔 잠깐 지워져야한다.

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
            selectRange(e);
        }
    }

    function selectRange(e) {
        $("#calendar_body tr td").removeClass('selected');
        mouseEvent.setEndDate(e); // 마우스 드래그 제일 끝 날짜 가져오기!!
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

    function schMouseClick(e) {
        e.preventDefault();
        var seq = $(e.target).data("seq"); // 일정이 출력되어있는 div 클릭 시 그 곳에 저장되어있는 글 번호(seq)를 가져온다.
        ajaxFunc.scheduleUpdateForm(seq); // 해당 글 번호를 보내버린다.
    }

    function schMouseOver(e) {
        e.preventDefault();
        $("#calendar_body tr td").off(); // 모든 이벤트 제거
        $(e.target).css("background-color", "green");
    }

    function schMouseLeave(e) {
        e.preventDefault();
        $(e.target).css("background-color", "orange");
        $(".schIn").off(); // 없애버렸다가 밑에서 다시 생성할거야~
        mouseEvent.init(); // td에 걸린 이벤트 다시 살리기
    }

    return { // 아래 init과 scheduleInsert는 어디서든지 접근할 수 있는 publick이 됨
        init: function () { // monthView와 weekView에서 호출해야되므로 public으로!
            $("#calendar_body tr td")
                .mousedown(rangeMouseDown)
                .mousemove(rangeMouseMove);

            $(".schIn")
                .click(schMouseClick)
                .mouseover(schMouseOver)
                .mouseleave(schMouseLeave);
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
    var schSeq; // 일정 글 번호 저장해두기

    var scheduleInsert = function () {
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
                alert("일정 등록 성공");
            },
            error : function() {
                alert("error");
            }
        });
    };

    var scheduleUpdateForm = function (seq) {
        $.ajax({
            url: 'http://calendar2.kr/calendar/scheduleGetBySeq', // 호출할 컨트롤러의 메소드
            data : {seq : seq},
            type : 'post',
            dataType : 'json',
            success : function (data) {
                console.log(data);
                schSeq = data.sch[0].seq; // 멤버 변수에 값 넣어놓기
                $("#updateContent").val(data.sch[0].content);
                $("#updateStart").text(data.sch[0].start_date); // 해당 기간을 p 태그에 출력!
                $("#updateEnd").text(data.sch[0].end_date); // 해당 기간을 p 태그에 출력!
                $("#updateModal").modal('show'); // show 모달!
            },
            error: function () {
                alert("error");
            }
        });
    };

    var scheduleUpdate = function () {
        var data = {
            seq : schSeq,
            content : $("#updateContent").val(),
            strDate : $("#updateStart").text(),
            endDate : $("#updateEnd").text()
        };
        $.ajax({
            url: 'http://calendar2.kr/calendar/scheduleModify', // 호출할 컨트롤러의 메소드
            data : data,
            type : 'post',
            dataType : 'json',
            success : function (data) {
                alert("업데이트 성공");
                $("#updateModal").modal('hide'); // show 모달!
            },
            error: function () {
                alert("error");
            }
        });
    };

    var scheduleDelete = function () {
        $.ajax({
            url: 'http://calendar2.kr/calendar/scheduleDelete', // 호출할 컨트롤러의 메소드
            data : {seq : schSeq},
            type : 'post',
            dataType : 'json',
            success : function (data) {
                alert("삭제 성공");
                $("#updateModal").modal('hide'); // show 모달!
            },
            error: function () {
                alert("error");
            }
        });
    };

    var monthSchedule = function(today, tempDay, date) {
        var view = ''; // 한 주에 하나씩
        $.ajax({
            url: 'http://calendar2.kr/calendar/scheduleGet', // 호출할 컨트롤러의 메소드
            data: { strDate : dateDraw.getCalendarStart(), // mouseEvent에 등록된 일정 시작일과 끝일을 가지고 온다.
                     endDate : dateDraw.getCalendarEnd() },
            type: 'post',
            dataType: 'json',
            async: false, // ajax 실행을 동기적으로 하겠다는 의미 (ajax는 비동기적 호출을 위한 함수로 하나의 작업 실행 후 다른 작업이 자동으로 실행되게 된다)
            success: function (data) {
                //alert(dateDraw.getCalendarStart());
                //alert(dateDraw.getCalendarEnd());
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
                            view += '<font color="red">'+ temp +'</font>';
                            tempDay++;
                        }
                        else if(tempDay == 6) //요일이 토요일 일때 글씨색을 파란색으로 한다.
                        {
                            view += '<font color="blue">'+ temp +'</font>';
                            tempDay++;
                        }
                        else //평일 일때 글씨색을 검정색으로 한다.
                        {
                            view += temp;
                            tempDay++;
                        }

                        for (var k = 0; k < data.cal.length; k++) {
                            if (data.cal[k].start_date == temp) { // 일정이 있는 날이면
                                view += '<div class="schIn" style="width: 100%; background-color: orange;" data-seq="'+data.cal[k].seq+'">'+ data.cal[k].content +'</div>';
                            }
                        }

                        view += '</td>';

                        if(tempDay > 6) //테이블의 새로운 행을 추가하도록 한다.
                        {
                            tempDay = 0;
                            view += '</tr>';
                            view += '<tr>';
                        }
                        date.setDate(date.getDate() + 1); // 1일 증가!
                    }
                }
                ajaxFunc.setView(view);
            },
            error: function () {
                alert("error");
            }
        });
    };

    var weekSchedule = function(today, date) {
        $.ajax({
            url: 'http://calendar2.kr/calendar/scheduleGet', // 호출할 컨트롤러의 메소드
            data: { strDate : dateDraw.getCalendarStart(), // mouseEvent에 등록된 일정 시작일과 끝일을 가지고 온다.
                     endDate : dateDraw.getCalendarEnd() },
            type: 'post',
            dataType: 'json',
            async: false, // ajax 실행을 동기적으로 하겠다는 의미 (ajax는 비동기적 호출을 위한 함수로 하나의 작업 실행 후 다른 작업이 자동으로 실행되게 된다)
            success: function (data) {
                var v = ''; // 해당 일(temp)의 td를 만들기 위한 변수

                for (var i = 0; i < 7; i++) {

                    var temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();

                    if (today == temp) // 오늘 날짜와 td에 들어갈 날짜가 같으면 (즉, 달력에 뿌려질 날짜가 오늘이면)
                    {
                        v += '<td style="background-color: gray;" data-year="'+date.getFullYear()+'" data-month="'+(date.getMonth()+1)+'" data-day="'+date.getDate()+'" data-cal="'+temp+'">';
                    }
                    else
                    {
                        v += '<td data-cal="'+temp+'">';
                    }

                    v += date.getDate()

                    for (var j = 0; j < data.cal.length; j++) {
                        if (data.cal[j].start_date == temp) { // 일정이 있는 날이면
                            v += '<div class="schIn" style="width: 100%; background-color: orange;" data-seq="'+data.cal[j].seq+'">'+ data.cal[j].content +'</div>';
                        }
                    }

                    v += '</td>';

                    date.setDate(date.getDate() + 1); // 해당 일 기준으로 다음날로 세팅
                 }
                ajaxFunc.setView(v);
            },
            error: function () {
                alert("error");
            }
        });
    };

    return {
        scheduleInsert : function () { // '일정등록' 버튼 클릭 시 호출되야해서 publick으로 해주고 내부 함수인 scheduleReg()를 호출되게 한다.
            scheduleInsert();
        },

        scheduleUpdateForm : function (seq) { // 일정이 출력되어있는 div 클릭 시
            scheduleUpdateForm(seq);
        },

        scheduleUpdate : function () { // 일정 수정 버튼 클릭 시 호출되는 ajax
            scheduleUpdate();
        },

        scheduleDelete : function () {
            scheduleDelete();
        },

        setView : function (v) {
            view = v;
        },

        getMonthView : function (today, tempDay, date) {
            monthSchedule(today, tempDay, date);
            return view;
        },

        getWeekView : function (today, date) {
            weekSchedule(today, date);
            return view;
        }
    }

})(); // ajaxFunc 모듈 End
