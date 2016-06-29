 <table id="day_list" cellpadding="0" cellspacing="0" style="width: 100%; border-bottom: 5px solid #d9d9d9;">
        <tbody>
        <tr>
            <th>일</th>
            <th>월</th>
            <th>화</th>
            <th>수</th>
            <th>목</th>
            <th>금</th>
            <th>토</th>
        </tr>
        </tbody>
    </table>

    <table class="calendar_main" cellpadding="0" cellspacing="0">
        <tbody id="calendar_body">

        </tbody>
    </table>
</div>

 <script src="../user_guide/_static/js/date.js"></script>

 <script type="text/javascript">
     $(function(){
         var flag; // '월' 버튼 상태일 경우 , '주' 버튼 상태일 경우 2로바꾸자

         var date = new Date();
         var year = date.getFullYear(); // 현재 로컬 시간의 년도를 구한다.
         var month = date.getMonth(); // 현재 로컬 시간의 월을 구한다.
         $("#ym").html(year + "년 " + (month+1) +"월"); // 현재 년도와 월을 입력한다.
         //var nowDate = date.getDate(); // 현재 '일'
         //var nowDay = date.getDay(); // 현재 '요일', 0부터 시작 (0-일요일, 1-월요일 ...)
         dateFunc.monthView(year, month);

         var weekDate;

         $("#month").click(function(e){ // '월' 을 눌렀을 경우
             flag = 1;
             e.preventDefault();
             $("#ym").html(year+"년 " + (month+1) +"월");
             dateFunc.monthView(year, month);
         }); // month End

         $("#week").click(function(e){ // '주'를 눌렀을 경우
             flag = 2;
             e.preventDefault();

             date = new Date(); // 현재 날짜 구하기
             date.setDate(date.getDate() - date.getDay()); // 현재 날짜의 첫 주 구하기
             dateFunc.weekView(date, 0); // 첫 주를 구했기 때문에 더할 숫자가 없다.
         }); // week End

         $('#prev').click(function(e){
             e.preventDefault();
             if (flag == 1) {
                 month = month - 1; // 이전이니까 월을 -1
                 if (month == -1) { // 현재 년도에서 더이상 이전 할 월이 없으면
                     year = year - 1;
                     month = 11; // 12월 값이다.
                 }
                 $("#ym").html(year+"년 " + (month+1) +"월");
                 dateFunc.monthView(year, month);
             }
             else
             {
                 e.preventDefault();
                 dateFunc.weekView(date, -1); // 현재 주간 일요일에서 이전 주간의 일요일 날짜를 구하기 위해 -1이 필요!
             }
         }); // prev End

         $("#next").click(function(e){
             e.preventDefault();
             if (flag == 1) { // '월' 버튼 클릭 시
                 month = month + 1;
                 if (month == 12) {
                     year = year + 1;
                     month = 0; // 1월 값이다.
                 }
                 $("#ym").html(year+"년 " + (month+1) +"월");
                 dateFunc.monthView(year, month);
             }
             else { // '주' 버튼 클릭 시
                 e.preventDefault();
                 dateFunc.weekView(date, 1); // 현재 주간 일요일에서 다음 주간 일요일 날짜를 구하기 위해 +1이 필요!
             }
         }); // next End
     });
 </script>