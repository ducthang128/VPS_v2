<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><strong>Danh sách Clip quảng cáo</strong></h3>
                <div class="input-group input-group-sm">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Tìm clip theo tên">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Tên Clip</th>
                  <th>Dung lượng</th>
                  <th>Thời lượng</th>
                  <th>Độ phân giải</th>
                  <th>Ngày đăng</th>
                  <th>Người đăng</th>
                  <th>Tình trạng</th>
                  <th>Hành động</th>
                </tr>
                <tr>
                  <td>183</td>
                  <td>John Doe</td>
                  <td>11-7-2014</td>
                  <td>sadedxasdqwxsx</td>
                  <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                  <td>John Doe</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-success">Approved</span></td>
                  <td>11-7-2014</td>
                </tr>
                <tr>
                  <td>219</td>
                  <td>Alexander Pierce</td>
                  <td>11-7-2014</td>
                  <td>asasasasasas</td>
                  <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                  <td>John Doe</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-success">Approved</span></td>
                  <td>11-7-2014</td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
    <!-- /.box -->
    </div>
<!-- /.col -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><strong>Đăng tải nội dung mới</strong></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <form id="upload_form" enctype="multipart/form-data" method="post" action="/manage/clipupload">
                    <div>
                        <div><input type="file" name="image_video_file" id="image_video_file" onchange="fileSelected();" />
                        <input type="checkbox" name="overwrite" title="Cập nhật tập tin mới"/>Cập nhật nội dung mới (Tập tin đã có sẽ bị xóa)</div>
                    </div>
                    <div>
                        <input type="submit" value="Upload"/>
                    </div>
                    <div id="fileinfo">
                        <div id="filename"></div>
                        <div id="filesize"></div>
                        <div id="filetype"></div>
                        <div id="filedim"></div>
                    </div>
                    <div id="error">Chỉ chọn đăng tải dạng video clip.</div>
                    <div id="error2">Lỗi trong quá trình đăng tải.</div>
                    <div id="abort">Quá trình truyền đã bị hủy bởi người dùng hoặc mất kết nối.</div>
                    <div id="warnsize">Tập tin đã chọn có dung lượng quá lớn, vui lòng điều chỉnh lại.</div>

                    <div id="progress_info">
                        <div id="progress"></div>
                        <div id="progress_percent">&nbsp;</div>
                        <div class="clear_both"></div>
                        <div>
                            <div id="speed">&nbsp;</div>
                            <div id="remaining">&nbsp;</div>
                            <div id="b_transfered">&nbsp;</div>
                            <div class="clear_both"></div>
                        </div>
                        <div id="upload_response"></div>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
        </div>
    <!-- /.box -->
    </div>
<!-- /.col -->
</div>




