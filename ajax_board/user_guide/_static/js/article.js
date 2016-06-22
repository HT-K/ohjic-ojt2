/**
 * Created by ohjic on 2016-06-21.
 */

var article = {
    /* 모든  게시글을 보여주는 페이지 */
    articleAll : function(context, href_url) {
       //alert(context+href_url);
        $.getJSON(context+href_url, function(data){
            //alert("success");
            //alert(data.page.totalArticle);
            var articleAll =
                '<div class="container">\
                <h2>게시판 예제</h2>\
                <p>게시판 글 목록 보여주기</p>\
                <table class="table table-striped">\
                <thead>\
                <tr>\
                <th>글 번호</th>\
                <th>글 제목</th>\
                <th>작성자</th>\
                </tr>\
                </thead>\
                \
                <tbody id="article_list">';

            for (var i = 0; i < data.board.length; i++)
            {
                articleAll +=
                    '<tr>\
                    <td>' + data.board[i].seq + '</td>\
                        <td><a href="/board/detail?'+data.board[i].seq+'">'+ data.board[i].title +'</a></td>\
                        <td>' + data.board[i].writer_name + '</td>\
                        </tr>';
            }

            articleAll +=
                '<tr>\
                <td colspan="3" style="text-align: center;">\
                <nav>\
                <ul class="pagination">';

            if (data.page.startPage - data.page.pageSize > 0) {
                articleAll +=
                    '<li>\
                    <a class="otherPage" href="/board/board?nowPage='+(data.page.startPage - data.page.pageSize)+'&keyField='+ data.page.keyField +'&keyWord='+ data.page.keyWord +'" aria-label="Previous">\
                    <span aria-hidden="true">&laquo;</span>\
                    </a>\
                    </li>';
            }

            for (var i = data.page.startPage; i <= data.page.endPage; i++) {
                if (i == data.page.nowPage) {
                    articleAll +=
                        '<li class="active"><a class="otherPage" href="/board/board?nowPage='+ data.page.nowPage +'&keyField='+ data.page.keyField +'&keyWord='+ data.page.keyWord +'">'+ data.page.nowPage +'<span class="sr-only">(current)</span></a></li>';
                }
                else {
                    articleAll +=
                        '<li><a class="otherPage" href="/board/board?nowPage='+i+'&keyField='+data.page.keyField+'&keyWord='+data.page.keyWord+'">'+i+'</a></li>';
                }
            }

            if (data.page.startPage + data.page.pageSize < data.page.totalPage) {
                articleAll +=
                    '<li>\
                    <a class="otherPage" href="/board/board?nowPage='+ (data.page.startPage + data.page.pageSize) +'&keyField='+data.page.keyField+'&keyWord='+ data.page.keyWord +'" aria-label="Next">\
                        <span aria-hidden="true">&raquo;</span>\
                        </a>\
                        </li>';
            }

            articleAll +=
                '</ul>\
                </nav>\
                </td>\
                </tr>\
                </tbody>\
                <tr>\
                <td colspan="3" style="text-align: center;">\
                <form style="margin: 0; padding: 0;" action="/board/board" method="get">\
                <select id="keyField" name="keyField">\
                <option value="title" selected="selected">제목</option>\
                <option value="writer_name">이름</option>\
                </select>\
                <input type="text" id="keyWord" name="keyWord" placeholder="검색어를 입력해주세요"/>\
                <input type="submit" id="searchBtn" name="searchBtn" value="검색" />\
                </form>\
                </td>\
                </tr>\
                </table>\
                </div>';

            $('#content').html(articleAll);

            // 이전 페이지 혹은 다음 페이지들 클릭 시 해당 a 태그의 href 값을 getJSON에 넘겨준다.
            // 동시에 생성되는 a 태그가 눌려지는 것을 인식하기 위해서는 클래스 이름으로 .click 접근해야 한다.
            $('.otherPage').click(function(e){
                e.preventDefault();
                var href_url = $(this).attr('href');
                article.articleAll(context, href_url);
            });

            // 검색 버튼 클릭 시 keyFiled와 keyWord 값을 넘겨준다.
            $('#searchBtn').click(function(e){
                e.preventDefault();
                var nowPage = 1;
                var keyField =  $('select[id=keyField] option:selected').val();
                var keyWord = $('#keyWord').val();
                var href_url = "/board/board?nowPage="+nowPage+"&keyField="+keyField+"&keyWord="+keyWord;
                article.articleAll(context, href_url);
            });
        });
    },
}