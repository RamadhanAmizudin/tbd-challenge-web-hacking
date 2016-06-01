    <section class="content-header">
      <h1>
        List News
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
          <div class="box-header">
              <h3 class="box-title">List News</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
                <?php
                  $query = $mysql->query('SELECT * FROM tbl_news ORDER BY id ASC');
                  while($data = $query->fetch_object()):
                ?>
                  <tr>
                    <td><?php echo $data->id; ?></td>
                    <td><?php echo htmlspecialchars($data->title); ?></td>
                    <td><?php echo $data->date_created; ?></td>
                    <td><a href="?page=news.php&amp;act=delete&amp;id=<?php echo $data->id; ?>" class="btn btn-primary">Delete</a></td>
                  </tr>
                <?php endwhile; ?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
      </div>
      <!-- /.box -->

    </section>