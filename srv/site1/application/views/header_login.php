<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <!--<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" title="default" />-->
      <link rel="stylesheet" href="/css/themefix.css" type="text/css" media="screen" title="default" />
      <link rel="stylesheet" href="/css/tisneb.css" type="text/css" media="screen" title="default" />

      <!-- Google font -->
      <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css' />

<?php /* SV: Afvragen welke gebruiker, en dan kiezen welk CSS */ ?>
      
      <?php
      /* SV: Voorwaardelijk Logo en Kleurschema */
      $klant_id = 1;  // Luuk: Hier kan de waarde uit de tabel worden geplaatst
      switch ($klant_id) {
         case 1:
            echo '<link rel="stylesheet" href="/css/1-coop.css" type="text/css" media="screen" title="default" />';
            break;

         case 2:
            echo '<link rel="stylesheet" href="/css/2-zorg.css" type="text/css" media="screen" title="default" />';
            break;

         case 3:
            echo '<link rel="stylesheet" href="/css/3-woonzorg.css" type="text/css" media="screen" title="default" />';
            break;

         default:
            echo '<link rel="stylesheet" href="/css/1-coop.css" type="text/css" media="screen" title="default" />';
            break;
      }
      ?>

<?php /* SV: EINDE Afvragen welke gebruiker, en dan kiezen welk CSS */ ?>

      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      <script type="text/javascript">
         $(document).ready(function () {
            $('form:first *:input[type!=hidden]:first').focus();
         });
      </script>
   </head>

<?php /* SV: <body> tag toegevoegd */ ?>
   <body style='margin-top: 0px;'>
