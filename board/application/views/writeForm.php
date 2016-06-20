<div class="container">
    <form action="/board/write" method="post">
        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="제 목">
        </div>
        <div class="form-group">
            <label for="writerName">작성자</label>
            <input type="text" class="form-control" id="writerName" name="writerName" placeholder="작 성 자">
        </div>
        <div class="form-group">
            <label for="password">글암호</label>
            <input type="text" class="form-control" id="password" name="password" placeholder="비 밀 번 호">
        </div>
        <div class="form-group">
            <label for="content">글내용</label>
            <textarea class="form-control" id="content" name="content" rows="5" placeholder="글 내 용"></textarea>
        </div>
        <button type="submit" id="writeSubmit" name="writeSubmit" class="col-xs-6 btn btn-success btn-lg">등 록</button>
        <button type="reset" id="writeReset" name="writeReset" class="col-xs-6 btn btn-warning btn-lg">취 소</button>
    </form>
</div>