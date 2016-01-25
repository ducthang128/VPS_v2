<div class="row">
<div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><strong>Thông tin đơn hàng</strong></h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <?php
                    if (isset($list_invoice['html']) && $list_invoice['html'] != '')
                    {
                        echo $list_invoice['html'];
                    }
                ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>

<?php
    if (isset($list_invoice['debug']) && $list_invoice['debug'] != '')
    {
        echo $list_invoice['debug'];
    }
?>