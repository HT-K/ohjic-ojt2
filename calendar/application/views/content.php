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

    <table class="calendar_main" >
        <tbody id="calendar_body">
        <!-- 달력 내용이 들어감 -->
        </tbody>
    </table>
</div>

 <!-- 일정 등록 modal 창 -->
 <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title">Schedule Registration</h4>
             </div>
             <div class="modal-body">
                 <form>
                     <div class="form-group">
                         <label for="scheduleContent" class="control-label">일정 내용</label>
                         <input type="text" class="form-control" id="scheduleContent">
                     </div>
                     <div class="form-group">
                         <label for="scheduleTime" class="control-label">시간</label>
                         <p id="scheduleTime"><!--내가 선택한 시간이 들어갈 곳--></p>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" id="sch_reg_btn" class="btn btn-primary">일정 등록</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
             </div>
         </div>
     </div>
 </div>

 <script src="../user_guide/_static/js/date.js"></script>

 <script type="text/javascript">
     $(function(){
         var flag = 1; // '월' 버튼 상태일 경우 , '주' 버튼 상태일 경우 2로바꾸자

         var date = new Date();
         var year = date.getFullYear(); // 현재 로컬 시간의 년도를 구한다.
         var month = date.getMonth(); // 현재 로컬 시간의 월을 구한다.
         //var nowDate = date.getDate(); // 현재 '일'
         //var nowDay = date.getDay(); // 현재 '요일', 0부터 시작 (0-일요일, 1-월요일 ...)
         dateFunc.monthView(year, month);

         $("#month").click(function(e){ // '월' 을 눌렀을 경우
             flag = 1;
             e.preventDefault();
             date = new Date();
             year = date.getFullYear(); // 현재 로컬 시간의 년도를 구한다.
             month = date.getMonth();
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
                 dateFunc.monthView(year, month);
             }
             else { // '주' 버튼 클릭 시
                 e.preventDefault();
                 dateFunc.weekView(date, 1); // 현재 주간 일요일에서 다음 주간 일요일 날짜를 구하기 위해 +1이 필요!
             }
         }); // next End


       /*  var strDate;
         var endDate;
         $('#calendar_body tr td').on({
            mousedown : function(e){
                strDate = e.target.textContent; // 클릭한 td의 값을 가져온다.
                //console.log(e.target.textContent);
            },
             mouseup : function(e){
                 endDate = e.target.textContent; // 클릭한 td의 값을 가져온다.
                 $("#scheduleTime").html(strDate + " ~ " + endDate); // 해당 기간을 p 태그에 출력!
                 $("#scheduleModal").modal('show'); // show 모달!
                 //console.log(e.target);

             }
         });*/

         /*$("#calendar_body").selectable({ // 달력에서 내가 날짜를 선택했을 경우
            stop : function(){ // 드래그 해서 날짜를 선택했을 경우
                $("#scheduleModal").modal('show'); // show 모달!
            }
         }); // calendar body End*/

     });
 </script>

 <script type="text/javascript">
     function init() {
         $("#calendar_body tr td")
             .mousedown(rangeMouseDown)
             .mouseup(rangeMouseUp)
             .mousemove(rangeMouseMove);
     }

     var dragStart = 0;
     var dragEnd = 0;
     var isDragging = false;
     var strDate;
     var endDate;

     function rangeMouseDown(e) {
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
             $("#scheduleTime").html(strDate + " ~ " + endDate); // 해당 기간을 p 태그에 출력!
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

     $("#sch_reg_btn").click(function(e){ // '일정 등록' 버튼을 눌렀을 때
         e.preventDefault();
         var data = {
             content : $("#scheduleContent").val(),
             strDate : strDate,
             endDate : endDate
         };
         $.ajax({
             url : 'http://calendar.kr/calendar/register', // 호출할 컨트롤러의 메소드
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
     }); // sch_reg_btn End

 </script>
