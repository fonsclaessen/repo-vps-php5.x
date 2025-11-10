<?php
$this->load->view('header_login');


?>
   <!-- LOGIN  PAGINA  -->
<?php /* SV: COOP Site "ZZP-er kan kijken" */ ?>
<?php /* SV: Logo inloggen aangepast en in DIV gezet */ ?>


<div id="inlog" style='margin-top: 0px;'>
   <div class='logo_header'>
      <div class='logo_coop_klant'></div>
   </div>
   <!--<img src="/images/img/Logo-Coop&Co.png" style="width:600px; height:160px;">-->
   <?php
   $koptekst = true;
    
	if ((trim($username) == "") || (trim($username) == "Gebruiker invoeren") || (trim($password) == "")){
		$user_logged_in_error = 0;
	}

   if ($user_ww_vergeten_email_verzonden == 1) {
      echo "<p>Er is een email met uw wachtwoord verzonden.</p>";
      $koptekst = false;
   }

   if ($user_logged_in_error == 1) {
      echo "<p>De naam of het wachtwoord klopt niet, probeer het nog eens..</p>";
      $koptekst = false;
   }
   if ($user_logged_in_error == 5) {
      echo "<p>U heeft 15 maal foutief ingelogged, vraag een nieuw wachtwoord aan.</p>";
      $koptekst = false;
   }

   $username = "Gebruiker invoeren";

   if ($koptekst == true) {
      ?>
      <h3>Coop & Co Leden aanmelden</h3>

      	<!-- <?php echo "VERSION: " . $CI_VERSION; ?> -->
		<!-- <?php 
			$CI =& get_instance();
			echo "-". $CI->config->item('ci_version'); 
		?> -->

   <?php } ?>
   
<!--   <h1>Momenteel wordt onderhoud uitgevoerd op de website. U kunt mogelijk enige vertraging ervaren.</h1>-->
<!--	<h2>In verband met onderhoud zijn vandaag niet alle facturen in het overzicht beschikbaar.</h2>-->
   <form id="qrevents-login" action="/index.php/users/login" method="post">
      <p class="p">Gebruiker</p><input name="username" id="username" value="<?php echo $username; ?>" placeholder="Gebruiker invoeren" size="15" type="text" class="login-inp" />
      <p class="p">Wachtwoord</p><input name="password" id="password"value="<?php echo $password; ?>" placeholder="Wachtwoord invoeren" size="15" type="password" class="login-inp" />
	  <br style="clear: both;">
	  
      <input zzzclass="login1knop" name="submit" id="submit" value="Aanmelden >" size="8"	type="submit"><!--
      --><input name="submitwwvergeten" id="submitwwvergeten" value="Wachtwoord vergeten" size="8" type="submit">
   </form>
</div>
<?php
$this->load->view('footer_login');
?>