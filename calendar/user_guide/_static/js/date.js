var dateFunc = {
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
        return date.getDay(); // 해당 year, month의 시작 요일을 구해서 리턴한다.
    }, // getStartDate End

    monthView : function(date, monthCalc){
        var title =  date.getFullYear()+"년 " + (date.getMonth()+1) +"월"; // 상단에 출력하게 될 연도와 월
        var nowTotalDate = dateFunc.getTotalDate(date.getFullYear(), date.getMonth()); // 현재 달의 총 일 수
        var startDay = dateFunc.getStartDay(date.getFullYear(), date.getMonth()); // 현재 달의 시작 요일
        date = new Date(date.getFullYear(), date.getMonth() + monthCalc, 1); // 현재 '달'의 1일 구하기
        date.setDate(date.getDate() - date.getDay()); // 현재 '달'의 첫주 일요일 날짜 구함

        var view = '<tr>';
        for (var i = 0; i < startDay; i++) {
            view += '<td><font color="gray">'+ date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() +'</td>';
            date.setDate(date.getDate() + 1); // 1일 씩 늘린다!
        }

        // 현재 달의 날짜가 들어가는 반복문
        for (var i = 1; i <= nowTotalDate; i++) {
            if(startDay == 0) //요일이 일요일 일때 글씨색을 붉은색으로 한다.
            {
                view += '<td><font color="red">'+ date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() +'</font></td>';
                date.setDate(date.getDate() + 1); // 1일 씩 늘린다!
                startDay++;
            }
            else if(startDay == 6) //요일이 토요일 일때 글씨색을 파란색으로 한다.
            {
                view += '<td><font color="blue">'+ date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() +'</font></td>';
                date.setDate(date.getDate() + 1); // 1일 씩 늘린다!
                startDay++;
            }
            else //평일 일때 글씨색을 검정색으로 한다.
            {
                view += '<td>'+ date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() +'</td>';
                date.setDate(date.getDate() + 1); // 1일 씩 늘린다!
                startDay++;
            }

            if(startDay > 6) //테이블의 새로운 행을 추가하도록 한다.
            {
                startDay = 0;
                view += '</tr>';
                view += '<tr>';
            }
        }

        if (startDay < 7) { // 현재 달의 맨 마지막 주에 공간이 남으면 다음 달 1일부터 채워넣는다!
            for (i = 1; startDay <= 6; i++) {
                view += '<td><font color="gray">'+ date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() +'</td>';
                date.setDate(date.getDate() + 1); // 1일 씩 늘린다!
                startDay++;
            }
        }

        date.setDate(date.getDate() - 20); // 다시 현재 '월'의 중간 날로 세팅

        $("#ym").html(title);
        $("#calendar_body").html(view); // 해당 달의 view를 tbody 안에 띄워준다
    },

    weekView : function(date, week){ // '주' 달력을 그려주기 위한 함수
        date.setDate(date.getDate()+ week * 7); // 현재 주간의 시작 '일요일'로 세팅!

       var title = date.getFullYear()+"년 " + (date.getMonth()+1) +"월 " + date.getDate() +"일 ~ "; // 주간 시작 날짜

        // 세팅된 현재 주간의 일요일 날짜부터 토요일날짜까지 +1 씩 해주면서 td에 세팅한다.
        var view = '<tr>';
        for (var i = 0; i < 7; i++) {
            view += '<td>'+ date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() +'</td>';
            date.setDate(date.getDate() + 1); // 해당 주간 세팅
        }
        view += '</tr>';

        title += date.getFullYear()+"년 " + (date.getMonth()+1) +"월 " + (date.getDate()-1) +"일";

        date.setDate(date.getDate() - 7); // 다시 현재 주간의 일요일로 세팅

        $("#ym").html(title);
        $("#calendar_body").html(view);
    } //weekView End


    // month 달력 구하는 방법 1
   /* monthView : function(year, month) { // '월' 달력을 그려주기 위한 함수
        var nowTotalDate = dateFunc.getTotalDate(year, month); // 현재 달의 총 일 수
        var preTotalDate = dateFunc.getTotalDate(year, month-1); // 이전 달의 총 일 수
        var startDay = dateFunc.getStartDay(year, month); // 현재 달의 시작 요일
        var start_preDate = preTotalDate - startDay + 1; // 현재 달력에서 이전 달의 시작하는 일자를 구한다.

        var view = '<tr>';

        // 현재 달에 남은 자리가 있으면 이전 달의 날짜를 구해서 넣는다. (현재 달의 시작이 목요일이면 월~수는 이전달의 숫자가 들어가야 한다)
        for (var i = 0; i < startDay; i++) {
            view += '<td><font color="gray">'+ start_preDate +'</td>';
            start_preDate++;
        }

        // 현재 달의 날짜가 들어가는 반복문
        for (var i = 1; i <= nowTotalDate; i++) {
            if(startDay == 0) //요일이 일요일 일때 글씨색을 붉은색으로 한다.
            {
                view += '<td><font color="red">'+i+'</font></td>';
                startDay++;
            }
            else if(startDay == 6) //요일이 토요일 일때 글씨색을 파란색으로 한다.
            {
                view += '<td><font color="blue">'+i+'</font></td>';
                startDay++;
            }
            else //평일 일때 글씨색을 검정색으로 한다.
            {
                view += '<td>'+i+'</td>';
                startDay++;
            }

            if(startDay > 6) //테이블의 새로운 행을 추가하도록 한다.
            {
                startDay = 0;
                view += '</tr>';
                view += '<tr>';
            }
        }

        if (startDay < 7) { // 현재 달의 맨 마지막 주에 공간이 남으면 다음 달 1일부터 채워넣는다!
            for (i = 1; startDay <= 6; i++) {
                view += '<td><font color="gray">'+i+'</td>';
                startDay++;
            }
        }

        $("#calendar_body").html(view); // 해당 달의 view를 tbody 안에 띄워준다
    }, // monthView End*/

}