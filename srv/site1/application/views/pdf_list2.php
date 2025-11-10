<?php
if (1==2) {
	$this->load->view ( 'header' );
} else {
	$this->load->view ( 'header_grid' );
}
?>

<table border="0" width="100%" cellpadding="0" cellspacing="0"	id="content-table">
	<tr>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>

		<div id="content-table-inner">

		<div id="table-content">
		<div>
		<!--
		<h1>jaar: ]<?php echo $jaartal_filtered; ?>[</h1>
		<h1>id: ]<?php echo $werknemer_id; ?>[</h1>
		-->
		<div style="float: left;"><button class="popup_week fancybox.iframe a_pdfpopupweek" href="/index.php/pdf/pdfpopupweek">Overzicht uren per week</button></div>
		<div style="float: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		<div style="float: left;"><button class="prevnextyearknupke" ONCLICK="window.location.href='/index.php/pdf/prevyear_grid'"><</button></div>	
		<div style="float: left;" class="prevnextyear"><h1 class="prevnextyear">&nbsp;<?php echo $jaartal_filtered; ?>&nbsp;</h1></div>
		<div style="float: left;"><button class="prevnextyearknupke" ONCLICK="window.location.href='/index.php/pdf/nextyear_grid'">></button></div>	
		<div style="float: left; padding-left: 100px; margin: 10px 0px 0px 0px"><h1><?php echo "" . $company_name; ?></h1></div>	
		<?php if ($werknemer_id==171) { ?>
		<?php } ?>
		<div style="float: right;padding-right: 10px;"><button class="a_pdfpopupweek" ONCLICK="ajax_pdf_download(<?php echo $werknemer_id . ', ' . $jaartal_filtered;?>, 1)">Facturen overzicht</button></div>

		<?php if ($is_factoring==1) { ?>
			<div style="float: right;padding-right: 10px;"><button class="a_pdfpopupweek" ONCLICK="openUrl()">Factoring aanvraag</button></div>

			
			
			

<script>
function openUrl() {
	
	var url = "nee";
	var werknemerID = -1;
	var theToken = "---";
	
	$.get("/index.php/users/getWerknemerID", function(id){
		werknemerID = id;
		console.log (werknemerID);
		if (werknemerID !== -1) {
		
			$.ajax({
				type: "POST",
				url: "https://iqmatrix.azurewebsites.net/IQ/InlogFactoring",
				data: JSON.stringify({ "werknemerID": werknemerID }),
				contentType: "application/json",
				success: function(data) {
				console.log(data);
				theToken = data.token;
				console.log("token>>>1 ",  theToken);
			
				if (theToken !== "") {
					console.log("inside!!! ",  theToken);
					var width = 900;
					var height = 600;
					var url = "https://factoring2024.azurewebsites.net/" + theToken;
					//alert(url);
					console.log (werknemerID);
					console.log (url);
					openPopup(url, width, height);		
				}
			},
				error: function(xhr, textStatus, errorThrown) {
					console.error(xhr.responseText);
				}
			});			
		}
	});
}

function openPopup(url, width, height) {
  $features = "fullscreen=yes"
  var popup = window.open(url, '_blank',' height='+screen.height+', width='+screen.width);
  if (popup) {
    popup.focus();
  } else {
    alert('Popup blocked! Please enable popups for this website.');
  }
}

</script>

			
		<?php } ?>
		
	</div>
	<br class="clear">
	<br class="clear">
	<table id="jqGrid"></table>
    <div id="jqGridPager"></div>
<!--	<script type="text/javascript" src="/js/grid_20171012.js"></script>	 -->
<!--	<script type="text/javascript" src="/js/grid_20230906.js?12"></script>	 -->
     	<script type="text/javascript" src="/js/grid_folder_2025_07_14.js?14"></script>	 	
    
	<script type="text/javascript"> 
		
	//	alert("TEST1");
        $(document).ready(function () {
		//	alert("TEST2");
        });

 
   </script>



	
	<div class="clear"></div>
</div>
	</td>
	<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
</table>
<br>
<?php
$this->load->view ( 'footer' );
?>


		