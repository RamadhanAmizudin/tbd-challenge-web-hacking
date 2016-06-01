<?php
switch(request('act')) {
    default:
        break;

    case 'delete':
            $mysql->query("DELETE FROM tbl_news WHERE id = " . get('id'));
        break;

    case 'add':
        if(isPost()) {
            $title = post('title');
            $content = post('content');
            if($title && $content) {
                $mysql->query("INSERT INTO tbl_news (title, content) VALUES ('" . $mysql->real_escape_string($title) . "', '" . $mysql->real_escape_string($content) . "')");
            }
        }
?>
       <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add News
              </h3>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <form action="?page=news.php&amp;act=add" method="POST">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" class="form-control" placeholder="Title." name="title">
                </div>
                <div class="form-group">
                  <label>Text</label>
                     <textarea class="textarea" placeholder="Content" name="content" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
                <!-- <a href="?page=upimg.php&amp;act=add&amp;csrftoken=">Upload Image</a> -->
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
<?php
        break;
}
