<div class="container">
    <form action="/board/write" method="post">
        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= $article->title ?>" readonly="readonly">
        </div>
        <div class="form-group">
            <label for="writerName">작성자</label>
            <input type="text" class="form-control" id="writerName" name="writerName" value="<?= $article->writer_name ?>" readonly="readonly">
        </div>
        <div class="form-group">
            <label for="password">글암호</label>
            <input type="text" class="form-control" id="password" name="password" value="<?=$article->password ?>" readonly="readonly">
        </div>
        <div class="form-group">
            <label for="content">글내용</label>
            <textarea class="form-control" id="content" name="content" rows="5" readonly="readonly"><?= $article->content ?></textarea>
        </div>
        <button type="submit" id="modBtn" name="modBtn" class="col-xs-6 btn btn-success btn-lg">수 정</button>
        <button type="reset" id="resBtn" name="resBtn" class="col-xs-6 btn btn-warning btn-lg">취 소</button>
    </form>
</div>