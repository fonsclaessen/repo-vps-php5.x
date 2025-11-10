<?php
$this->load->view ( 'header_login' );
?>
<body id="login-bg"> 
<div id="login-holder">
<div id="logo-login"></div>

<div class="clear"></div>

<div id="loginbox">
<div id="login-inner">
<?php
if ($user_logged_in_error == 1) {
	echo "<p>De naam of het wachtwoord klopt niet, probeer het nog eens..</p>";
} else {
?>
	<h1><strong>Coop & Co Leden Login</strong></h1>
<?php } ?>
<br>
<form id="qrevents-login" action="/index.php/users/login" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<th>User:</th>
			<td><input name="username" id="username"
				value="<?php
				echo $username;
				?>" placeholder="user name" size="15"
				type="text" class="login-inp" /></td>
		</tr>
		<tr>
			<th>Wachtwoord:</th>
			<td><input name="password" id="password"
				value="<?php
				echo $password;
				?>" placeholder="wachtwoord" size="15"
				type="password" class="login-inp" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th></th>
			<td><input name="submit" id="submit" value="Login" size="8"
				type="submit"></td>
		</tr>
	</tbody>
</table>
</form>
</div>
</div>
</div>
<?php
$this->load->view ( 'footer_login' );
?>