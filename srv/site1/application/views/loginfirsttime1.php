<?php $this->load->view ( 'header_login' ); ?>

<body id="login-bg"> 
<div id="login-holder">
<div id="logo-login"></div>

<div class="clear"></div>

<div id="loginbox">
<div id="login-inner">
<?php
if ($user_logged_in_error == 1) {
		echo "<p>De paswoorden komen niet overeen, probeer het nog eens..</p>";
} else {
?>
		<h1><strong>Geef nieuw wachtwoord voor eerste keer inloggen</strong></h1>
<?php } ?>
<br>
<form id="qrevents-login" action="/index.php/users/firstlogin" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<th>wachtwoord:</th>
			<td><input name="password1" id="password1"
				value="<?php
				echo $password1;
				?>" placeholder="wachtwoord" size="15"
				type="text" class="login-inp" /></td>
		</tr>
		<tr>
			<th>Nogmaals wachtwoord:</th>
			<td><input name="password2" id="password2"
				value="<?php
				echo $password2;
				?>" placeholder="wachtwoord" size="15"
				type="text" class="login-inp" /></td>
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
<?php $this->load->view ( 'footer_login' ); ?>

