<?php
switch(request('act')) {
    default:
    case 'add':
        if(isPost()) {
            echo "<h2>Invalid File Type.</h2>";
        }
?>
       <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Image
              </h3>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <!-- TODO: Fix upload form -->
              <form action="?page=upimg.php&amp;act=add" method="POST">
                <div class="form-group">
                  <label>Image File</label>
                  <input type="file" class="form-control" placeholder="Valid Ext: jpg,png,gif,bmp" name="imgfile">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
<?php
        break;
}
