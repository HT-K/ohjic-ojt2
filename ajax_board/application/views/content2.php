<div class="container">
    <h2>게시판 예제</h2>
    <p>게시판 글 목록 보여주기</p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>글 번호</th>
            <th>글 제목</th>
            <th>작성자</th>
        </tr>
        </thead>
        <tbody id="article_list">
        <!-- 여기에 게시글 목록이 들어갈 것이다. -->
        <tr>
            <td colspan="3" style="text-align: center;">
                <nav>
                    <ul class="pagination">
                        <!-- 현재 페이지가 4이고 pageSize가 3일 때 <<(이전페이지)버튼을 누르면 자동으로 1페이지에 있는 게시글이 보이도록 설정! -->
                        <?php if ($page['startPage'] - $page['pageSize'] > 0): ?>
                            <li>
                                <!-- 현재 페이지가 4이고 pageSize가 3일 때 <<(이전페이지)버튼을 누르면 자동으로 1페이지에 있는 게시글이 보이도록 설정! -->
                                <a href="/board/board?nowPage=<?php echo $page['startPage'] - $page['pageSize'] ?>&keyField=<?php echo $page['keyField'] ?>&keyWord=<?php echo $page['keyWord']?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif ?>

                        <?php for ($i = $page['startPage']; $i <= $page['endPage']; $i++) { ?>
                            <?php if ($i == $page['nowPage']) { ?>
                                <!-- 현재 페이지는 파란색 백그라운드 효과 -->
                                <li class="active"><a href="/board/board?nowPage=<?php echo $page['nowPage'] ?>&keyField=<?php echo $page['keyField'] ?>&keyWord=<?php echo $page['keyWord']?>"><?php echo $page['nowPage'] ?><span class="sr-only">(current)</span></a></li>
                            <?php }
                            else { ?>
                                <!-- 현재 클릭한 페이지를 제외한 나머지는 하얀색 백그라운드 -->
                                <li><a href="/board/board?nowPage=<?php echo $i ?>&keyField=<?php echo $page['keyField'] ?>&keyWord=<?php echo $page['keyWord']?>"><?php echo $i ?>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>

                        <!-- 시작 페이지 번호가 7이고 한 페이지의 페이지 최대 수가 3일 때, 총 게시물 수를 이용해 구한 totalPage가 9라면 다음 페이지를 알리는 '>>'가 보이지 말아야한다. -->
                        <?php if ($page['startPage'] + $page['pageSize'] < $page['totalPage']) : ?>
                            <li>
                                <!-- startPage 가 1이고 한번에 보여줄 페이지 수(pageSize)가 3일 때 >>(다음페이지)버튼을 누르면 4가 보여져야하므로 startPage+pageSize를 pageNo에 담아서 보낸다. -->
                                <a href="/board/board?nowPage=<?php echo $page['startPage'] + $page['pageSize'] ?>&keyField=<?php echo $page['keyField'] ?>&keyWord=<?php echo $page['keyWord']?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif ?>
                    </ul>
                </nav>
            </td>
        </tr>
        </tbody>
        <tr>
            <td colspan="3" style="text-align: center;">
                <form style="margin: 0; padding: 0;" action="/board/board?nowPage=<?php echo $page['nowPage'] ?>" method="get">
                    <select id="keyField" name="keyField">
                        <!-- DB에 접근할 떄 value와 db컬럼명이 같아야한다. -->
                        <option value="title" selected="selected">제목</option>
                        <option value="writer_name">이름</option>
                    </select>
                    <input type="text" id="keyWord" name="keyWord" placeholder="검색어를 입력해주세요"/>
                    <input type="submit" id="searchBtn" name="searchBtn" value="검색" />
                </form>
            </td>
        </tr>
    </table>
</div>

<script src="../user_guide/_static/js/article.js"></script>


<script type="text/javascript">
    /* 메인 메소드라고 보면 된다. */


    $(function(){
        $.ajax({
            url : 'http://ajaxboard.kr/board/board',
            data : {
                nowPage : <?php echo $page['nowPage'] ?>
            },
            dataType : 'JSON',
            type : 'GET',
            success : function(data) {
                var article_list;
                for (var i = data.board.length - 1; i >= 0; i--)
                {
                    // 첫번째 줄엔 '+' 기호를 쓰면 안된다.
                    article_list =
                          '<tr>'
                        + '<td>' + data.board[i].seq + '</td>'
                        + '<td><a href="/board/detail?' + data.board[i].seq + '">' + data.board[i].title + '</a></td>'
                        + '<td>' + data.board[i].writer_name + '</td>'
                        + '</tr>';
                    $('#article_list').prepend(article_list);
                }
            },
            error : function(request,status,msg) {
                alert("code:" + request.status+"\n"+"message:"+request.responseText+"\n"+"msg:"+msg);
            }
        });

        $('#searchBtn').click(function(e){
            e.preventDefault();
        });
    });
</script>
