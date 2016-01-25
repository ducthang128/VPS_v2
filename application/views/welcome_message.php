<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BES - Bus Entertainment System - Management Portal</title>

    <!-- css -->
    <link href="/bes/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/bes/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="/bes/css/nivo-lightbox.css" rel="stylesheet" />
	<link href="/bes/css/nivo-lightbox-theme/default/default.css" rel="stylesheet" type="text/css" />
	<link href="/bes/css/owl.carousel.css" rel="stylesheet" media="screen" />
    <link href="/bes/css/owl.theme.css" rel="stylesheet" media="screen" />
	<link href="/bes/css/flexslider.css" rel="stylesheet" />
	<link href="/bes/css/animate.css" rel="stylesheet" />
    <link href="/bes/css/style.css" rel="stylesheet">
	<link href="/bes/color/default.css" rel="stylesheet">

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">
	<!-- Section: home intro -->
    <section id="intro" class="home-intro text-light">
		<div class="home-intro-wrapper">

		</div>
    </section>
	<!-- /Section: intro -->


    <!-- Navigation -->
    <div id="navigation">
        <nav class="navbar navbar-custom" role="navigation">
                              <div class="container">
                                    <div class="row">
                                          <div class="col-md-2 mob-logo">
                                                <div class="row">
                                                      <div class="site-logo">
															<h1><a href="/"><strong>BES</strong></a></h1>
                                                      </div>
                                                </div>
                                          </div>


                                          <div class="col-md-10 mob-menu">
                                                <div class="row">
                                                      <!-- Brand and toggle get grouped for better mobile display -->
                                              <div class="navbar-header">
                                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                                                    <i class="fa fa-bars"></i>
                                                    </button>
                                              </div>
                                                      <!-- Collect the nav links, forms, and other content for toggling -->
                                                      <div class="collapse navbar-collapse" id="menu">
                                                            <ul class="nav navbar-nav navbar-right">
                                                                  <li class="active"><a href="#intro">Trang chủ</a></li>
																   <li><a href="#service">Dịch vụ</a></li>
                                                                  <li><a href="#works">Chính sách</a></li>
                                                                    <li><a href="#Registra">Đăng ký</a></li>
                                                                  <?php
                                                                  if (isset($auth_user_name) && $auth_user_name != '')
                                                                  {
                                                                    echo '<li data-toggle="tooltip" data-placement="right" title="Click to Logout">'.secure_anchor('/userauth/logout','Chào '.$auth_user_name).'</li>';
                                                                  }
                                                                  else
                                                                  {
                                                                    echo '<li><a href="login">Đăng nhập</a></li>';
                                                                  }
                                                                  ?>
                                                            </ul>
                                                      </div>
                                                      <!-- /.Navbar-collapse -->
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <!-- /.container -->
                        </nav>
    </div>
    <!-- /Navigation -->



	<!-- Section: parallax 1 -->
	<section id="parallax1" class="home-section parallax text-light" data-stellar-background-ratio="0.5">
           <div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="text-center">
						<h2 class="big-heading highlight-dark wow bounceInDown" data-wow-delay="0.2s">SaigonBus - Saigon Smart Solutions - Intel VietNam</h2>
                        <h2 class="big-heading highlight-dark wow bounceInDown" data-wow-delay="1s">Kết Nối Thành Công</h2>
						</div>
					</div>
				</div>
            </div>
	</section>

	<!-- Section: services -->
    <section id="service" class="home-section color-dark bg-white">
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="wow flipInY" data-wow-offset="0" data-wow-delay="0.4s">
					<div class="section-heading text-center">
					<h2 class="h-bold">Bus Entertainment System</h2>
					<div class="divider-header"></div>
					<p style="font-family:sans-serif;">Hệ thống quản lý dịch vụ giải trí trên xe buýt (BES) là kết quả của sự hợp tác giữa Công ty Cổ phần TIE và Công ty Intel VietNam. Hệ thống nhằm mang lại sự thoải mái, an toàn cho người tham gia giao thông bằng xe vận tải công cộng.</p>
					</div>
					</div>
				</div>
			</div>

		</div>

		<div class="text-center">
		<div class="container">

        <div class="row">
            <div class="col-xs-6 col-sm-3 col-md-3">
				<div class="wow fadeInLeft" data-wow-delay="0.2s">
                <div class="service-box">
					<div class="service-icon">
						<span class="glyphicon glyphicon-signal" style="font-size: xx-large;"></span>
					</div>
					<div class="service-desc">
						<h5>Wifi Internet</h5>
						<p style="font-family:sans-serif;">
						Kết nối internet thông suốt đảm bảo công việc và học tập của hành khách không bao giờ bị gián đoạn dù bất cứ ở đâu
						</p>
						<a href="#" class="btn btn-skin">Chi tiết...</a>
					</div>
                </div>
				</div>
            </div>
			<div class="col-xs-6 col-sm-3 col-md-3">
				<div class="wow fadeInUp" data-wow-delay="0.2s">
                <div class="service-box">
					<div class="service-icon">
						<span class="glyphicon glyphicon-hd-video" style="font-size: xx-large;"></span>
					</div>
					<div class="service-desc">
						<h5>Camera an ninh và hành trình</h5>
						<p style="font-family:sans-serif;">
						Mang lại cảm giác an toàn cho hành khách trong suốt hành trình.
						</p>
						<a href="#" class="btn btn-skin">Chi tiết...</a>
					</div>
                </div>
				</div>
            </div>
			<div class="col-xs-6 col-sm-3 col-md-3">
				<div class="wow fadeInUp" data-wow-delay="0.2s">
                <div class="service-box">
					<div class="service-icon">
						<span class="glyphicon glyphicon-shopping-cart" style="font-size: xx-large;"></span>
					</div>
					<div class="service-desc">
						<h5>Thông tin quảng cáo</h5>
						<p style="font-family:sans-serif;">
						Cập nhật và mang đến nhhững thông tin bổ ích về sản phẩm và công nghệ đến người tiêu dùng
						</p>
						<a href="#" class="btn btn-skin">Chi tiết...</a>
					</div>
                </div>
				</div>
            </div>
			<div class="col-xs-6 col-sm-3 col-md-3">
				<div class="wow fadeInRight" data-wow-delay="0.2s">
                <div class="service-box">
					<div class="service-icon">
						<span class="glyphicon glyphicon-globe" style="font-size: xx-large;"></span>
					</div>
					<div class="service-desc">
						<h5>Định vị thời gian thực</h5>
						<p style="font-family:sans-serif;">
						Cập nhật theo thời gian thực lộ trình của chuyến đi và báo cáo cho hành khách theo thời gian thực.
						</p>
						<a href="#" class="btn btn-skin">Chi tiết...</a>
					</div>
                </div>
				</div>
            </div>
        </div>
		</div>
		</div>
	</section>
	<!-- /Section: services -->

	<!-- Section: parallax 2 -->
	<section id="parallax2" class="home-section parallax text-light" data-stellar-background-ratio="0.5">
           <div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="text-center">
						<h2 class="big-heading highlight-dark wow bounceInDown" data-wow-delay="0.2s">Cùng bạn trên những ngã đường, giải trí và làm việc trong thế giới "luôn kết nối"</h2>
						</div>
					</div>
				</div>
            </div>
	</section>


	<!-- Section: works -->
    <section id="works" class="home-section color-dark text-center bg-white">
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="wow flipInY" data-wow-offset="0" data-wow-delay="0.4s">
					<div class="section-heading text-center">
					<h2 class="h-bold">Giá trị vượt trội</h2>
					<div class="divider-header"></div>
					<p>Hệ thống quảng cáo tiếp cận hàng triệu lượt khách hàng mỗi ngày, chủ động quản lý nội dung cho từng chiến dịch quảng cáo. Chi phí hợp lý, hiệu quả tối đa.</p>
					</div>
					</div>
				</div>
			</div>

		</div>

		<div class="container">
			<div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12" >
					<div class="wow bounceInUp" data-wow-delay="0.4s">
                    <div id="owl-works" class="owl-carousel">
                        <div class="item"><a href="img/works/1.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/1@2x.jpg"><img src="/bes/img/works/1.jpg" class="img-responsive" alt="img"></a></div>
                        <div class="item"><a href="img/works/2.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/2@2x.jpg"><img src="/bes/img/works/2.jpg" class="img-responsive " alt="img"></a></div>
                        <div class="item"><a href="img/works/3.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/3@2x.jpg"><img src="/bes/img/works/3.jpg" class="img-responsive " alt="img"></a></div>
                        <div class="item"><a href="img/works/4.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/4@2x.jpg"><img src="/bes/img/works/4.jpg" class="img-responsive " alt="img"></a></div>
                        <div class="item"><a href="img/works/5.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/5@2x.jpg"><img src="/bes/img/works/5.jpg" class="img-responsive " alt="img"></a></div>
                        <div class="item"><a href="img/works/6.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/6@2x.jpg"><img src="/bes/img/works/6.jpg" class="img-responsive " alt="img"></a></div>
                        <div class="item"><a href="img/works/7.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/7@2x.jpg"><img src="/bes/img/works/7.jpg" class="img-responsive " alt="img"></a></div>
                        <div class="item"><a href="img/works/8.jpg" title="This is an image title" data-lightbox-gallery="gallery1" data-lightbox-hidpi="img/works/8@2x.jpg"><img src="/bes/img/works/8.jpg" class="img-responsive " alt="img"></a></div>
                    </div>
					</div>
                </div>
            </div>
		</div>

	</section>
	<!-- /Section: works -->

	<!-- Section: parallax 3 -->
	<section id="parallax3" class="home-section parallax text-light text-center" data-stellar-background-ratio="0.5">
           <div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="testimonialslide clearfix flexslider">
							<ul class="slides">
								<li><blockquote>
								Gắn bó với nhiệm vụ phát triển hệ thống vận tải hành khách công cộng bằng xe buýt của Thành phố Hồ Chí Minh ngay từ những ngày đầu thành lập, hoạt động Buýt của Công ty THNN Một thành viên Xe khách Sài Gòn (SaigonBus) được biết đến như hoạt động truyền thống của công ty góp phần không nhỏ vào việc phát triển kinh tế, văn hóa, du lịch và đáp ứng kịp thời nhu cầu của nhiều người dân
									</blockquote>
									<h4>Giới thiệu <span>&#8213; SaigonBus</span></h4>
								</li>
								<li><blockquote>
								Với hơn 500 xe và 32 tuyến phục vụ trong địa bàn thành phố và các vùng lân cận như Bình Dương, Tây Ninh, SaiGonBus là thương hiệu gần gũi, quen thuộc với người dân thành phố hằng ngày. Đây cũng là lĩnh vực chủ chốt của Công ty đã được UBND Tp Hồ Chí Minh tin tưởng giao phó. Hiện tại công ty đang phục vụ đi lại của hơn 130.000 người với 3.500 chuyến một ngày.
									</blockquote>
									<h4>Hoạt động <span>&#8213; SaigonBus </span></h4>
								</li>
							</ul>
						</div>
					</div>
				</div>
            </div>
	</section>


	<!-- Section: Registra -->
    <section id="Registra" class="home-section nopadd-bot color-dark bg-white text-center">
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="wow flipInY" data-wow-offset="0" data-wow-delay="0.4s">
					<div class="section-heading text-center">
					<h2 class="h-bold">Đăng ký tham gia</h2>
					<div class="divider-header"></div>
					<p>Đăng ký tham gia hệ thống quảng cáo tiếp cận hàng triệu lượt người xem mỗi ngày để gia tăng cơ hội kinh doanh.</p>
					</div>
					</div>
				</div>
			</div>

		</div>

		<div class="container">

			<div class="row marginbot-80">
				<div class="col-md-8 col-md-offset-2">
						<form id="Registra-form" action="welcome/reg" method="POST" class="wow bounceInUp" data-wow-offset="10" data-wow-delay="0.2s">
						<div class="row marginbot-20">
							<div class="col-md-6 xs-marginbot-20">
								<input type="text" class="form-control input-lg" id="first_name" name="first_name" placeholder="Họ" required="required" />
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control input-lg" id="last_name" name="last_name" placeholder="Tên" required="required" />
							</div>

						</div>
                        <div class="row">
							<div class="col-md-12">
                                <div class="form-group">
    								<input type="text" class="form-control input-lg" id="user_name" name="user_name" placeholder="Tên tài khoản" required="required" />
    							</div>
                                <div class="form-group">
    								<input type="email" class="form-control input-lg" id="email" name="email" placeholder="Địa chỉ email" required="required" />
    							</div>
                                <div class="form-group">
    								<input type="text" class="form-control input-lg" id="company" name="company" placeholder="Tên Công ty" required="required" />
    							</div>
                                <div class="form-group">
    								<input type="text" class="form-control input-lg" id="address" name="address" placeholder="Địa chỉ liên hệ" required="required" />
    							</div>
                                <div class="form-group">
    								<input type="text" class="form-control input-lg" id="phone" name="phone" placeholder="Điện thoại liên hệ" required="required" />
    							</div>
								<div class="form-group">
									<textarea name="message" id="message" class="form-control" rows="4" cols="25" placeholder="Thông tin bổ sung khác"></textarea>
								</div>
								<button type="submit" class="btn btn-skin btn-lg btn-block" id="btnRegistration">
									Đăng ký</button>
							</div>
						</div>
						</form>
				</div>
			</div>


		</div>
	</section>
	<!-- /Section: Registra -->

	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">

					<div class="text-center">
						<a href="#intro" class="totop"><i class="pe-7s-angle-up pe-3x"></i></a>

						<div class="social-widget">


							<ul class="team-social">
									<li class="social-facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
									<li class="social-twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
									<li class="social-google"><a href="#"><i class="fa fa-google-plus"></i></a></li>
									<li class="social-dribbble"><a href="#"><i class="fa fa-dribbble"></i></a></li>
							</ul>

						</div>
						<p>Bus Entertainment System, Ho Chi Minh City, Viet Nam<br />
						&copy;Copyright 2015 - SGS. All rights reserved.<br/>
						Design by <a href="http://bes.saigonsolutions.com.vn" rel="nofollow">Saigon Smart Solutions</a></p>
					</div>
				</div>
			</div>
		</div>
	</footer>

    <!-- Core JavaScript Files -->
    <script src="/bes/js/jquery.min.js"></script>
    <script src="/bes/js/bootstrap.min.js"></script>
	<script src="/bes/js/jquery.sticky.js"></script>
	<script src="/bes/js/jquery.flexslider-min.js"></script>
	<script src="/bes/js/morphext.min.js"></script>
    <script src="/bes/js/jquery.easing.min.js"></script>
	<script src="/bes/js/jquery.scrollTo.js"></script>
	<script src="/bes/js/jquery.appear.js"></script>
	<script src="/bes/js/stellar.js"></script>
	<script src="/bes/js/wow.min.js"></script>
	<script src="/bes/js/owl.carousel.min.js"></script>
	<script src="/bes/js/nivo-lightbox.min.js"></script>
    <script src="/bes/js/custom.js"></script>
</body>

</html>
