<div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> Ghi chú:</h4>
        <ul>
            <li>Khi cung cấp những thông tin dưới đây, quý khách đã xem và đồng ý với các điều khoản cùng quy tắc làm việc của hệ thống.</li>
            <li>Nội dung đơn hàng đăng ký phát quảng cáo trên hệ thống xe Bus do khách hàng nhập bên dưới này là một phần không thể tách rời của hợp đồng quảng cáo.</li>
            <li>Khách hàng sẽ không thể thay đổi nội dung sau khi đã hoàn tất và gửi thông tin đến hệ thống của chúng tôi.</li>
            <li>Đơn hàng có giá trị trong thời gian tối đa 7 ngày, trong thời gian này nếu khách hàng không hoàn tất thủ tục ký hợp đồng và thanh toán thì đơn hàng sẽ không còn giá trị.</li>
            <li>Ngoài ra, chúng tôi không chịu trách nhiệm về khoảng thời gian mà quý khách hàng đã đăng ký không còn khả dụng do chậm trễ khi thanh toán.</li>
        </ul>
        Cảm ơn quý khách lựa chọn sử dụng dịch vụ của chúng tôi.
      </div>
</div>
<section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Saigon Smart Solution Ltd., Co.
            <small class="pull-right">Ngày:
            <?php
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                echo date('d-m-Y');
            ?>
            </small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Người gửi:
          <address>
            <strong>Ban quản lý hệ thống quảng cáo trên xe Bus</strong><br>
            Công ty TNHH Saigon Smart Solution<br>
            Công ty TNHH MTV SaigonBus<br>
            Phone: (+84) 909102321<br>
            Email: info@saigonsmartsolutions.com.vn
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Khách hàng:
          <address>
            <strong>
            <?php
                if (isset($user_info['first_name']) && isset($user_info['last_name']))
                {
                    echo $user_info['first_name']. ' ' .$user_info['last_name'];
                }
            ?>
            </strong><br>
            <?php
                if (isset($user_info['company']) && $user_info['company'] != '')
                {
                    echo $user_info['company'];
                }
            ?>
            <br>
            <?php
                if (isset($user_info['address']) && $user_info['address'] != '')
                {
                    echo $user_info['address'];
                }
            ?>
            <br>
            Điện thoại:
            <?php
                if (isset($user_info['phone']) && $user_info['phone'] != '')
                {
                    echo $user_info['phone'];
                }
            ?>
            <br>
            Email:
            <?php
                if (isset($user_info['email']) && $user_info['email'] != '')
                {
                    echo $user_info['email'];
                }
            ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Mã đơn hàng:
          <?php
                if (isset($user_info['oder_id']) && $user_info['oder_id'] != '')
                {
                    echo '#'.$user_info['oder_id'];
                }
          ?>
          </b><br>
          <br>
          <b>Ngày đặt hàng:
          <?php
                if (isset($user_info['create_update']) && $user_info['create_update'] != '')
                {
                    echo date("d/m/Y",strtotime($user_info['create_update']));
                }
          ?>
          </b>
          <br>
          <b>Mã khách hàng:
          <?php
                if (isset($user_info['user_name']) && $user_info['user_name'] != '')
                {
                    echo $user_info['user_name'];
                }
          ?>
          </b>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
    <div class="row">
        <script src="/bes/manage/dist/js/moment.min.js"></script>
        <script src="/bes/manage/dist/js/daterangepicker.js"></script>
        <script src="/bes/manage/plugins/ionslider/ion.rangeSlider.min.js"></script>
        <form action="/manage/create_invoice/<?php if (isset($user_info['oder_id']) && $user_info['oder_id'] != '') { echo $user_info['oder_id']; } ?>" method="post" enctype="multipart/form-data">
            <?php if (isset($list) && $list != ''){ echo $list;} ?>

        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
              <p class="lead">Phương thức thanh toán:</p>
              <img src="/bes/img/visa.png" alt="Visa">
              <img src="/bes/img/mastercard.png" alt="Mastercard">
              <img src="/bes/img/chuyenkhoan.png" alt="Bank Transfer">

              <div class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                <p>Quý khách có thể chuyển tiền vào tài khoản của hệ thống thông qua hình thức chuyển khoản ngân hàng hoặc thanh toán trực tiếp tại văn phòng làm việc.</p>
                <strong><ins>Thông tin tài khoản</ins></strong>
                <ul>
                    <li>Ngân hàng Toàn Cầu</li>
                    <li>Chủ tài khoản: Công ty SGS</li>
                    <li>Số tài khoản: 1234567890</li>
                </ul>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
              <p class="lead">Tổng đơn hàng (<?php if (isset($user_info['create_update']) && $user_info['create_update'] != '') { echo date("h:m - d/m/Y",strtotime($user_info['create_update'])); }else{ echo date("h:m - d/m/Y");} ?>)
              </p>

              <div class="table-responsive">
                <table class="table">
                  <tbody><tr>
                    <th style="width:50%">Tổng tiền dịch vụ:</th>
                    <td style="text-align: right;"><?php if(isset($totalprice)&&$totalprice != ''){echo $totalprice;} ?></td>
                  </tr>
                  <tr>
                    <th>Thuế VAT (10%)</th>
                    <td style="text-align: right;"><?php if(isset($vat)&&$vat != ''){echo $vat;} ?></td>
                  </tr>
                  <tr>
                    <th>Phụ phí:</th>
                    <td style="text-align: right;">0 đ</td>
                  </tr>
                  <tr>
                    <th>Tổng tiền cần thanh toán:</th>
                    <td style="text-align: right;"><?php if(isset($total_invoice)&&$total_invoice != ''){echo $total_invoice;} ?></td>
                  </tr>
                </tbody></table>
              </div>
            </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="/manage/invoice_print/<?php if (isset($user_info['oder_id']) && $user_info['oder_id'] != '') { echo $user_info['oder_id']; } ?>.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> In đơn hàng</a>
          <button type="button" class="btn btn-primary btn-danger pull-right" style="margin-right: 5px;">
            <i class="fa fa-ban"></i> Hủy đơn hàng
          </button>
          <button type="button" onclick="location.reload()" class="btn btn-primary btn-info pull-right" style="margin-right: 5px;">
            <i class="fa fa-check"></i> Cập nhật đơn hàng
          </button>
        </div>
      </div>
    </form>
</section>