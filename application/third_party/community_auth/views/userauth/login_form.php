<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Login Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

if( ! isset( $on_hold_message ) )
{
	if( isset( $login_error_mesg ) )
	{
		echo '
			<div style="border:1px solid red;">
				<p>
					Login Error: Invalid Username, Email Address, or Password.
				</p>
				<p>
					Username, email address and password are all case sensitive.
				</p>
			</div>
		';
	}
?>
<div class="content">
<?php

if( ! isset( $optional_login ) )
{
	echo '<h3 class="form-title">Đăng nhập hệ thống</h3>';
}
	echo form_open( $login_url, array( 'class' => 'std-form' ) );
?>


			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label for="login_string" class="control-label visible-ie8 visible-ie9">Tên người dùng</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-user"></i>
						<input class="m-wrap placeholder-no-fix" type="text" autocomplete="off" placeholder="Tên người dùng" maxlength="255" name="login_string" id="login_string"/>
					</div>
				</div>
			</div>
		<br />
			<div class="control-group">
				<label for="login_pass" class="control-label visible-ie8 visible-ie9">Mật khẩu</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix" type="password" autocomplete="off" placeholder="Mật khẩu" name="login_pass" id="login_pass" maxlength="<?php echo config_item('max_chars_for_password'); ?>"/>
					</div>
				</div>
			</div>


		<?php
			if( config_item('allow_remember_me') )
			{
		?>

			<br />

			<label for="remember_me" class="form_label">Ghi nhớ phiên đăng nhập</label>
			<input type="checkbox" id="remember_me" name="remember_me" value="yes" />

		<?php
			}
		?>

		<p>
			<a href="<?php echo secure_site_url('examples/recover'); ?>">
				Bạn đã quên mật khẩu?
			</a>
		</p>
            <div class="form-actions">
				<!--<label class="checkbox">
				<input type="checkbox" name="remember" value="1"/> Tự động đăng nhập lần sau
				</label>-->
				<button type="submit" name="submit" class="btn blue pull-right" id="submit_button">
				Đăng nhập <i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>

	</div>

</form>

<?php

	}
	else
	{
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
			<div style="border:1px solid red;">
				<p>
					Excessive Login Attempts
				</p>
				<p>
					You have exceeded the maximum number of failed login<br />
					attempts that this website will allow.
				<p>
				<p>
					Your access to login and account recovery has been blocked for ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.
				</p>
				<p>
					Please use the ' . secure_anchor('examples/recover','Account Recovery') . ' after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
					or contact us if you require assistance gaining access to your account.
				</p>
			</div>
		';
	}
 	if( $this->input->get('logout') )
	{
		echo '
			<div style="border:1px solid green; text-align:center;">
				<p>Bạn đã ngừng kết nối với hệ thống</p>
			</div>
		';
	}
?>
 <?php
/* End of file login_form.php */
/* Location: /views/examples/login_form.php */