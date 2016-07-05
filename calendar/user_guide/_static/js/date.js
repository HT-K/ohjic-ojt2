var dateObj = (function (){

    return {
        getTotalDate : function(year, month){ // 해당 달의 총 일수를 구한다.
            if (month==3 || month==5 || month==8 || month==10) {
                return 30;
            } else if (month == 1) {
                if ((year % 4 == 0  && year % 100 != 0 ) || year % 400 == 0) {
                    return 29;
                } else {
                    return 28;
                }
            } else {
                return 31;
            }
        }, // getTotalDate End

        getStartDay : function(year, month){ // 해당 달의 시작 일을 구한다.
            var date = new Date();
            date.setFullYear(year);
            date.setMonth(month);
            date.setDate(1);
            return date // 해당 year, month의 시작 요일을 구해서 리턴한다.
        } // getStartDate End
    }

})(); // dateObj 모듈 End

var dateDraw = (function () {
    var nowDate; // 현재 주간화면을 백업해놓기 위한 변수

    return {
        monthView : function(year, month) { // '월' 달력을 그려주기 위한 함수
            $("#ym").html(year+"년 " + (month+1) +"월");
            var today = new Date();
            today = today.getFullYear()+ "-" + (today.getMonth()+1) + "-" + today.getDate(); // 현재 날짜에 배경색을 칠하기 위해 미리 구해놓음.
            var nowTotalDate = dateObj.getTotalDate(year, month); // 현재 달의 총 일 수
            var preTotalDate = dateObj.getTotalDate(year, month-1); // 이전 달의 총 일 수
            var date = dateObj.getStartDay(year, month); // 현재 달의 1일 구해오기
            var startDay = date.getDay(); // 현재 달의 1일의 '시작 요일' 구하기
            var start_preDate = preTotalDate - startDay + 1; // 현재 달력에서 이전 달의 시작하는 일자를 구한다.

            var view = '<tr>';

            // 현재 달에 남은 자리가 있으면 이전 달의 날짜를 구해서 넣는다. (현재 달의 시작이 목요일이면 월~수는 이전달의 숫자가 들어가야 한다)
            var befMonth = new Date(year, month-1, start_preDate); // 현재 달력에 회색으로 보이게 될 이전달 시작일을 설정해준다
            for (var i = 0; i < startDay; i++) {
                view += '<td><font color="gray">'+ befMonth.getFullYear() + "-" + (befMonth.getMonth()+1) + "-" + start_preDate +'</td>';
                start_preDate++;
            }

            // 현재 달의 날짜가 들어가는 반복문
            for (var i = 1; i <= nowTotalDate; i++) {
                var temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" +i; // td에 들어갈 날짜를 temp 변수에 저장

                if (today == temp) // 오늘 날짜와 td에 들어갈 날짜가 같으면 (즉, 달력에 뿌려질 날짜가 오늘이면)
                {
                    view += '<td style="background-color: gray;">'
                }
                else
                {
                    view += '<td>'
                }

                if(startDay == 0) //요일이 일요일 일때 글씨색을 붉은색으로 한다.
                {
                    view += '<font color="red">'+ temp +'</font></td>';
                    startDay++;
                }
                else if(startDay == 6) //요일이 토요일 일때 글씨색을 파란색으로 한다.
                {
                    view += '<font color="blue">'+ temp +'</font></td>';
                    startDay++;
                }
                else //평일 일때 글씨색을 검정색으로 한다.
                {
                    view += ''+ temp +'</td>';
                    startDay++;
                }

                if(startDay > 6) //테이블의 새로운 행을 추가하도록 한다.
                {
                    startDay = 0;
                    view += '</tr>';
                    view += '<tr>';
                }
            }

            if (startDay < 6) { // 현재 달의 맨 마지막 주에 공간이 남으면 다음 달 1일부터 채워넣는다!
                var nextMonth = new Date(date.getFullYear(), date.getMonth()+1, 1); // 다음 달 1일을 세팅해놓는다. (다음 달 1일부터 남은 공간에 출력하기 위함)
                for (i = 1; startDay <= 6; i++) {
                    view += '<td><font color="gray">'+ nextMonth.getFullYear() + "-" + (nextMonth.getMonth() + 1) + "-" +i+'</td>';
                    startDay++;
                }
            }

            $("#calendar_body").html(view); // 해당 달의 view를 tbody 안에 띄워준다
            mouseEvent.init(); // tbody에 마우스 이벤트 연결
        }, // monthView End

        weekView : function(date, week) { // '주' 달력을 그려주기 위한 함수
            date.setDate(date.getDate() + week * 7); // 현재 주간의 시작 '일요일'로 세팅!
            dateDraw.backUpWeekData(date); // 현재 주간 백업!
            var today = new Date();
            today = today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate(); // 현재 날짜에 배경색을 칠하기 위해 미리 구해놓음.

            var title = date.getFullYear() + "년 " + (date.getMonth() + 1) + "월 " + date.getDate() + "일 ~ "; // 주간 시작 날짜
            var temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate(); // 현재 주간의 '일요일' 이다.

            // 세팅된 현재 주간의 일요일 날짜부터 토요일날짜까지 +1 씩 해주면서 td에 세팅한다.
            var view = '<tr>';
            for (var i = 0; i < 7; i++) {
                temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                view += ajaxFunc.getView(today, temp); // 일정을 받아와서 뷰에 뿌려주기 위한 ajax
                date.setDate(date.getDate() + 1); // 해당 일 기준으로 다음날로 세팅
            }
            view += '</tr>';

            date.setDate(date.getDate() - 1); // 늘어난 1일을 잠시 -1 해서 세팅 (주간 달력에 글씨 띄우기 위함)

            title += date.getFullYear()+"년 " + (date.getMonth()+1) +"월 " + date.getDate() +"일"; // 주간 끝 날짜

            date.setDate(date.getDate() + 1);

            date.setDate(date.getDate() - 7); // 다시 현재 주간의 일요일로 세팅

            $("#ym").html(title);
            $("#calendar_body").html(view);
            mouseEvent.init(); // tbody에 마우스 이벤트 연결
        }, //weekView End

        backUpWeekData : function(date) {  // 주간 화면을 띄우기 위해 필요한 today, temp 를 백업시켜놓는다.
            nowDate = date;
        },

        getWeekData : function() {
            return nowDate;
        }
    }

})(); // dateDraw 모듈 End

//for (var i = 0; i < 7; i++) {
//    temp = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
//    /*$.getJSON('http://calendar.kr/calendar/scheduleGet?strDate='+temp, function(data){
//     alert(data.cal[0].content);
//     });*/
//
//    if (today == temp) // 오늘 날짜와 td에 들어갈 날짜가 같으면 (즉, 달력에 뿌려질 날짜가 오늘이면)
//    {
//        view += '<td style="background-color: gray;">';
//        //view += '<div>'+ data[i].content +'</div>';
//    }
//    else {
//        view += '<td>';
//    }
//
//    view += temp + '</td>';
//    date.setDate(date.getDate() + 1); // 해당 일 기준으로 다음날로 세팅
//}

/*$.ajax({
    url : 'http://calendar.kr/calendar/scheduleGet', // 호출할 컨트롤러의 메소드
    data : {strDate : temp},
    type : 'post',
    dataType : 'json',
    success : function(data) {
        console.log(data.cal[0].content);
    },
    error : function() {
        alert("error");
    }
}); // ajax End*!/*/

var mouseEvent = (function () {
    var dragStart = 0;
    var dragEnd = 0;
    var isDragging = false;
    var strDate;
    var endDate;

    function mouseDown (e) {
        if (isRightClick(e)) {
            return false;
        } else {
            var allCells = $("#calendar_body tr td");
            dragStart = allCells.index($(this));
            isDragging = true;

            //strDate = e.target.textContent; // 마우스 드래그 시작 날짜 가져오기!!
            mouseEvent.setStrDate(e);

            if (typeof e.preventDefault != 'undefined') { e.preventDefault(); }
            document.documentElement.onselectstart = function () { return false; };
        }
    }

    function mouseUp(e) {
        if (isRightClick(e)) {
            return false;
        } else {
            var allCells = $("#calendar_body tr td");
            dragEnd = allCells.index($(this));

            //endDate = e.target.textContent; // 마우스 드래그 제일 끝 날짜 가져오기!!
            mouseEvent.setEndDate(e);
            $("#scheduleStart").html(strDate); // 해당 기간을 p 태그에 출력!
            $("#scheduleEnd").html(endDate); // 해당 기간을 p 태그에 출력!
            $("#scheduleModal").modal('show'); // show 모달!

            isDragging = false;
            if (dragEnd != 0) {
                drawScope();
            }

            document.documentElement.onselectstart = function () { return true; };
        }
    }

    function mouseMove(e) {
        if (isDragging) {
            var allCells = $("#calendar_body tr td");
            dragEnd = allCells.index($(this));
            drawScope();
        }
    }


    function drawScope() { // 선택된 부분들에 배경 색칠하기!
        $("#calendar_body tr td").removeClass('selected');
        if (dragEnd + 1 < dragStart) { // reverse select
            $("#calendar_body tr td").slice(dragEnd, dragStart + 1).addClass('selected');
        } else {
            $("#calendar_body tr td").slice(dragStart, dragEnd + 1).addClass('selected');
        }
    }

    function isRightClick(e) { // 마우스 우클릭 방지
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
                .mousedown(mouseDown)
                .mouseup(mouseUp)
                .mousemove(mouseMove);
        },

        // 일정 시작일과 끝일을 getter / setter 로 만들어 놓는다.
        setStrDate : function(e) {
          //strDate = e.target.textContent;
          //console.log($(e.target).children(".selDate"));
            strDate = $(e.target).children(".selDate")[0].textContent; // 날짜 값 가져와서 설정!
        },

        getStrDate : function() {
          return strDate;
        },

        setEndDate : function(e) {
            //console.log($(e.target).children(".selDate"));
            endDate = $(e.target).children(".selDate")[0].textContent;
        },

        getEndDate : function() {
          return endDate;
        }


    };

})(); // mouseEvent 모듈 End

var ajaxFunc = (function () {
    var view; // month 혹은 week의 내용을 담는 변수

    var scheduleReg = function (flag) {
        var data = {
            content : $("#scheduleContent").val(),
            strDate : mouseEvent.getStrDate(), // mouseEvent에 등록된 일정 시작일과 끝일을 가지고 온다.
            endDate : mouseEvent.getEndDate()
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
                //$("#calendar_body tr td").removeClass('selected'); // 선택되어 있는 td에 있는 백그라운드 값을 없애버린다.
            },
            error : function() {
                alert("error");
            }
        });
    };

    var weekSchedule = function(today, temp) {

        $.ajax({
            url: 'http://calendar.kr/calendar/scheduleGet', // 호출할 컨트롤러의 메소드
            data: {strDate: temp},
            type: 'post',
            dataType: 'json',
            async: false, // ajax 실행을 동기적으로 하겠다는 의미 (ajax는 비동기적 호출을 위한 함수로 하나의 작업 실행 후 다른 작업이 자동으로 실행되게 된다)
            success: function (data) {
                var v = ''; // 해당 일(temp)의 td를 만들기 위한 변수

                if (today == temp) // 오늘 날짜와 td에 들어갈 날짜가 같으면 (즉, 달력에 뿌려질 날짜가 오늘이면)
                {
                    v += '<td style="background-color: gray;">';
                }
                else
                {
                    v += '<td>';
                }

                if (data.cal.length > 0) { // 일정이 있는 날이면
                    for (var i = 0; i < data.cal.length; i++) {
                        v += '<div style="width: 100%; height:20px; background-color: orange;">'+ data.cal[i].content +'</div>';
                    }
                }

                v +='<div class="selDate">' + temp + '</div>' +'</td>';
                ajaxFunc.setView(v);
            },
            error: function () {
                alert("error");
            }
        });
    };

    return {
        scheduleInsert : function (flag) { // '일정등록' 버튼 클릭 시 호출되야해서 publick으로 해주고 내부 함수인 scheduleReg()를 호출되게 한다.
            scheduleReg(flag);
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