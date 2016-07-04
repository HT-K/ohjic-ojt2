var dateObj = (function (){

    return {
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
    var date;

    return {
        monthView : function() { // '월' 달력을 그려주기 위한 함수

            mouseEvent.init(); // tbody에 마우스 이벤트 연결
        }, // monthView End

        weekView : function(week) { // '주' 달력을 그려주기 위한 함수
            date.setDate(date.getDate()+ week * 7); // 현재 주간의 시작 '일요일'로 세팅!

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
