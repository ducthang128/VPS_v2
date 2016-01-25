<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><strong>Danh sách Clip quảng cáo đang phát</strong></h3>
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
                      <th>Tuyến xe</th>
                      <th>Số xe</th>
                      <th>Thời điểm phát</th>
                    </tr>
                    <?php
                        if (isset($clip_playing) && count($clip_playing) > 0) {
                            $stt = 1;
                            foreach ($clip_playing as $row => $clip){
                                echo '<tr>';
                                echo '<td>'.$stt.'</td>';
                                echo '<td>'.$clip['filename'].'</td>';
                                echo '<td>Bến Thành - Bến xe Chợ Lớn</td>';
                                echo '<td>'.$clip['so_xe'].'</td>';
                                echo '<td>'.$clip['thoi_diem_phat'].'</td>';
                                echo '</tr>';
                            }
                        }
                    ?>
              </tbody></table>
              <script type="text/javascript">
              $(document).ready(function(){
                <?php
                    if (isset($clip_playing) && count($clip_playing) > 0){
                        echo 'setTimeout(function(){window.location.reload(1);}, 30000);';
                    }
                ?>
              });
            </script>
            </div>
            <!-- /.box-body -->
        </div>
    <!-- /.box -->
    </div>
<!-- /.col -->
</div>
