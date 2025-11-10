<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" title="default" />
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

	  
	<!--<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>-->
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	  

      <!-- Add mousewheel plugin (this is optional) -->
      <script type="text/javascript" src="/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <!-- Add fancyBox main JS and CSS files -->
      <script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.0.4"></script>
      <link rel="stylesheet" type="text/css" href="/fancybox/source/jquery.fancybox.css?v=2.0.4" media="screen" />
      <!-- Add fancyBox - button helper (this is optional) -->
      <link rel="stylesheet" type="text/css" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=2.0.4" />
      <script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=2.0.4"></script>
      <!-- Add fancyBox - thumbnail helper (this is optional) -->
      <link rel="stylesheet" type="text/css" href="/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=2.0.4" />
      <script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=2.0.4"></script>

      <script src="/js/ajaxpdf.js" type="text/javascript" ></script>
      <script src="/js/ajaxPdf2.js" type="text/javascript" ></script>
	  <script src="/js/download_ajax.js"></script>
      <script type="text/javascript">
         $(document).ready(function () {
            $(".various").fancybox({
               maxWidth: 920,
               maxHeight: 840,
               fitToView: false,
               width: '70%',
               height: '70%',
               autoSize: false,
               closeClick: false,
               openEffect: 'none',
               closeEffect: 'none'
            });


            //		maxWidth	: 380,
            //		maxHeight	: 400,

            $(".popup_week").fancybox({
               maxWidth: 520,
               maxHeight: 620,
               fitToView: false,
               //		width		: '70%',
               //		height		: '70%',
               width: '100%',
               height: '100%',
               autoSize: false,
               closeClick: false,
               openEffect: 'none',
               closeEffect: 'none'
            });
            $(".fiat_popup").fancybox({
               maxWidth: 1100,
               maxHeight: 760,
               fitToView: false,
               width: '100%',
               height: '100%',
               autoSize: false,
               closeClick: false,
               openEffect: 'none',
               closeEffect: 'none',
               afterClose: fancyclose_week
            });

            function fancyclose_week()
            {
               //	alert("close");

               window.location = "/index.php/pdf/fiatteren";  //ververs de Fiat en Afgekeurd velden na update in SQL
            }

         });
      </script>
	

	<!-- voor de charts -->
    <link rel="stylesheet" href="/jqwidgets/jqwidgets/styles/jqx.base.css" type="text/css" />
    <!--<script type="text/javascript" src="ts/jquery-1.11.1.min.js"></script>-->
    <script type="text/javascript" src="/jqwidgets/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="/jqwidgets/jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="/jqwidgets/jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="/jqwidgets/jqwidgets/jqxdata.js"></script>	  
	  
	
	<!-- The jQuery library is a prerequisite for all jqSuite products -->
    <!--<script type="text/ecmascript" src="../../../js/jquery.min.js"></script> -->
    <!-- We support more than 40 localizations -->
    <script type="text/ecmascript" src="/jqgrid/js/i18n/grid.locale-nl.js"></script>
    <!-- This is the Javascript file of jqGrid -->   
    <script type="text/ecmascript" src="/jqgrid/js/jquery.jqGrid.min.js"></script>
	
	
    <!-- This is the localization file of the grid controlling messages, labels, etc.
    <!-- A link to a jQuery UI ThemeRoller theme, more than 22 built-in and many more custom -->

    <link rel="stylesheet" type="text/css" media="screen" href="/jqgrid/css/jquery-ui.css" />
    <!-- The link to the CSS that the grid needs -->
    <link rel="stylesheet" type="text/css" media="screen" href="/jqgrid/css/ui.jqgrid.css" />
	
   </head>
   <body> 
   
      <div id="page-top-outer" data-title="Coopinfo">    

         <div id="page-top">

            <!--<div id="logo">
               Coop & Co		
            </div>-->
<div id="page-top-outer-grid"></div>

            <div class="clear"></div>
         </div>
      </div>


      <div class="clear">&nbsp;</div>

      <div class="nav-outer-repeat"> 

         <div class="nav-outer"> 


            <?php $this->load->view('menulinks'); ?>


         </div>
         <div class="clear"></div>
      </div>

      <div class="clear"></div>

      <div id="content-outer">

         <div id="content">
		 <!--<h1>TEST1</h1>-->