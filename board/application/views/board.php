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
        <tbody>
        <?php foreach ($board as $entry) { ?>
        <tr>
            <td><?php echo $entry->seq ?></td>
            <td><a href="/index.php/board/detail/<?php echo $entry->seq ?>"><?php echo $entry->title ?></a></td>
            <td><?php echo $entry->writer_name ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="3" style="text-align: center;">
                <nav>
                    <ul class="pagination">
                        <!-- 현재 페이지가 4이고 pageSize가 3일 때 <<(이전페이지)버튼을 누르면 자동으로 1페이지에 있는 게시글이 보이도록 설정! -->
                        <?php if ($page['startPage'] - $page['pageSize'] > 0) { ?>
                            <li>
                                <!-- 현재 페이지가 4이고 pageSize가 3일 때 <<(이전페이지)버튼을 누르면 자동으로 1페이지에 있는 게시글이 보이도록 설정! -->
                                <a href="/index.php/board/board/<?php echo $page['startPage'] - $page['pageSize'] ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php } ?>

                        <?php for ($i = $page['startPage']; $i <= $page['endPage']; $i++) { ?>
                            <?php if ($i == $page['nowPage']) { ?>
                                <!-- 현재 페이지는 파란색 백그라운드 효과 -->
                                <li class="active"><a href="/index.php/board/board/<?php echo $page['nowPage'] ?>"><?= $page['nowPage'] ?><span class="sr-only">(current)</span></a></li>
                            <?php }
                            else { ?>
                                <!-- 현재 클릭한 페이지를 제외한 나머지는 하얀색 백그라운드 -->
                                <li><a href="/index.php/board/board/<?php echo $i ?>"><?php echo $i ?></a></li>
                            <?php } ?>
                        <?php } ?>

                        <!-- 게시글이 21개 articleSize가 5라면 totalPages는 5이다. 토탈 페이지가 5이고 현재 화면에 끝 페이지가 3이라면 다음 페이지를 알리는 버튼을 보여준다 -->
                        <?php if ($page['totalPage'] - $page['endPage'] > 0) { ?>
                            <li>
                                <!-- startPage 가 1이고 한번에 보여줄 페이지 수(pageSize)가 3일 때 >>(다음페이지)버튼을 누르면 4가 보여져야하므로 startPage+pageSize를 pageNo에 담아서 보낸다. -->
                                <a href="/index.php/board/board/<?= $page['startPage'] + $page['pageSize'] ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </td>
        </tr>
        </tbody>
    </table>
</div>