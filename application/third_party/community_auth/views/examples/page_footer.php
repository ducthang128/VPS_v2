<?php
$show_menu = 0;
if (isset($show_menu) && $show_menu == 1){ ?>

<div id="menu">
	<ul>
		<li><?php
			if( isset( $auth_user_id ) ){
				echo secure_anchor('examples/logout','Logout');
			}else{
				echo secure_anchor( LOGIN_PAGE . '?redirect=examples','Login');
			}
		?></li>
		<li>
			<?php echo secure_anchor('examples/optional_login_test','Optional Login'); ?>
		</li>
		<li>
			<?php echo secure_anchor('examples/simple_verification','Simple Verification'); ?>
		</li>
	</ul>
</div>

<?php } ?>
<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		Bản quyền  © 2015 <a href="http://saigonsolutions.com.vn/" target="_blank" style="color:#70b243;    font-weight: bold;">SGS</a>
	</div>
	<!-- END COPYRIGHT -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   <script src="/bes/js/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="/bes/js/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="/bes/js/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="/bes/js/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!--[if lt IE 9]>
	<script src="/assets/plugins/excanvas.min.js"></script>
	<script src="/assets/plugins/respond.min.js"></script>
	<![endif]-->
	<script src="/bes/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="/bes/js/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="/bes/js/plugins/jquery.cookie.min.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="/bes/js/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
	<script src="/bes/js/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="/bes/js/plugins/select2/select2.min.js"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="/bes/js/app.js" type="text/javascript"></script>
	<script src="/bes/js/login-soft.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
	<script>
		jQuery(document).ready(function() {
		  App.init();
		  Login.init();
		});
	</script>
	<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>

<?php

/* End of file page_footer.php */
/* Location: /views/examples/page_footer.php */