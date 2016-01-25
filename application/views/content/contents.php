<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><strong>Danh sách Clip quảng cáo</strong></h3>
                <div class="input-group input-group-sm">
                  <input id="searchclip" type="text" name="table_search" class="form-control pull-right" placeholder="Tìm clip theo tên">
                  <div class="input-group-btn">
                    <button type="submit" onclick="window.location = '/manage/content/'+encodeURI(document.getElementById('searchclip').value)+'/'+'search';" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                      <th>Thứ tự</th>
                      <th>Tên Clip</th>
                      <th>Khung giờ phát</th>
                      <th>Thời lượng</th>
                      <th>Độ phân giải</th>
                      <th>Ngày đăng</th>
                      <th>Người đăng</th>
                      <th>Tình trạng</th>
                      <th>Hành động</th>
                    </tr>
                    <?php
                        if (isset($list_ads['result']) && $list_ads['result'] != '') { echo $list_ads['result'];}
                    ?>
              </tbody></table>
              <script type="text/javascript">
                function clip_action(clip,action){
                    var msg = '';
                    if (action == 'del') {
                        msg = 'Xóa Clip đã đăng \n';
                    } else if (action == 'disable') {
                        msg = 'Tạm khóa Clip đã đăng \n';
                    } else {
                        msg = 'Cho phép Clip đã ngưng \n';
                    }

                    var r = confirm(msg + "Bạn có chắc là muốn thực hiện thao tác này");
                    if (r == true) {
                        window.location = "/manage/content/"+clip+"/"+action;
                    } else {
                        alert("Bạn đã hủy thao tác");
                    }
                }
              </script>
            </div>
            <!-- /.box-body -->
        </div>
    <!-- /.box -->
    </div>
<!-- /.col -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><strong>Đăng tải nội dung mới</strong></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <form id="upload_form" enctype="multipart/form-data" method="post" action="/manage/clipupload">
                    <input type="file" name="image_video_file" id="image_video_file" onchange="fileSelected();" />
                    <input type="submit" value="Tải lên" />
                </form>
            </div>
            <!-- /.box-body -->
        </div>
    <!-- /.box -->
    </div>
<!-- /.col -->
</div>

<?php
    if (isset($list_ads['debug']) && $list_ads['debug'] != '') { echo $list_ads['debug'];}
?>


