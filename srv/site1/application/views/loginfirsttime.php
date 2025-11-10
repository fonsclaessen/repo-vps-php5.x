<?php $this->load->view ( 'header_login' ); ?>

<div id="inlog">
<img src="/images/img/2013062132215.png.orig.png" style="width:600px; height:132px;">
<?php
if ($user_logged_in_error == 1) {
		echo "<p>De wachtwoorden komen niet overeen, of zijn leeg, probeer het nog eens..</p>";
} else {
?>
		<p>Geef nieuw wachtwoord, minimale lengte 4 karakters.</p>
<?php } ?>
	<form id="qrevents-login" action="/index.php/users/firstlogin" method="post">
	<p class="p2">Nieuw</p>
	<input name="password1" id="password1" value="<?php echo $password1; ?>" placeholder="wachtwoord" size="15" type="text" class="login-inp" />
	<p class="p2">Nogmaals</p>
	<input name="password2" id="password2" value="<?php echo $password2; ?>" placeholder="wachtwoord" size="15" type="text" class="login-inp" />
	<input name="submit" id="submit" value="Aanmelden" size="8" type="submit">
	</form>

</div>
<?php $this->load->view ( 'footer_login' ); ?>

