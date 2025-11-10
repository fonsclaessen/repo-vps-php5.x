<?php
$this->load->view ( 'header' );
?>
<!-- div class="content">-->
<h1><?php echo "" . $company_name; ?></h1>
<p>&nbsp;</p>
<div>&nbsp;</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0"	id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img
			src="/images/shared/side_shadowleft.jpg" width="20" height="300"
			alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>

		<th rowspan="3" class="sized"><img
			src="/images/shared/side_shadowright.jpg" width="20" height="300"
			alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>

		<div id="content-table-inner">

	<div id="table-content">
	<div>
	<!--
	<div style="float: left;"><button class="popup_week fancybox.iframe a_pdfpopupweek" href="/index.php/pdf/pdfpopupweek">Overzicht uren per week</button></div>
	<div style="float: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
	<div style="float: left;"><button class="prevnextyearknupke" ONCLICK="window.location.href='/index.php/pdf/prevyear'"><</button></div>	
	<div style="float: left;" class="prevnextyear"><h1 class="prevnextyear">&nbsp;<?php echo $jaartal_filtered; ?>&nbsp;</h1></div>
		<div style="float: left;"><button class="prevnextyearknupke" ONCLICK="window.location.href='/index.php/pdf/nextyear'">></button></div>	
	</div>
	-->
	<br class="clear">
	<h1>Werkbriefjes fiatteren</h1>
	<?php echo $paginate_links; ?>
	<div>&nbsp;</div>
		<form id="qrevents-tekst" action="/index.php/texts/edit" method="post">
			<table border="0" width="100%" cellpadding="0" cellspacing="0"	id="product-table">
			<tr>
				<th class="table-header-repeat line-left"><a href="">Week</a></th>
				<th class="table-header-repeat line-left"><a href="">Jaar</a></th>
				<th class="table-header-repeat line-left"><a href="">Zzp naam</a></th>
				<th class="table-header-repeat line-left ZZZzzp_t_datum"><a href="">Project</a></th>
				<th class="table-header-repeat line-left zzp_t_uren"><a href="">Uren</a></th>
				<th class="table-header-repeat line-left zzp_t_datum"><a href="">Fiatteren</a></th>
			</tr>

			<tbody>
<?php
foreach ( $results->result() as $row ) {
	?>
			<tr class='table-row-odd'>
				<td align="left" valign="top" class="td-padding "><div><?php echo $row->week; ?></div></td>
				<td align="left" valign="top" class="td-padding "><div><?php echo $row->jaar; ?></div></td>
				<td align="left" valign="top" class="td-padding "><div><?php echo $row->company_name; ?></div></td>
				<td align="left" valign="top" class="td-padding "><div><?php echo $row->projectnr; ?></div></td>
				<td align="right" class="td-padding" valign="top"><?php echo formaturen($row->totaal); ?></td>
				<td ZZZalign="right" ZZZclass="td-padding" ZZZvalign="top"><button class="various fancybox.iframe a_pdfpopup" href="/index.php/pdf/fiatpopup/<?php echo $row->uren_id; ?>">Fiatteren</button></td>
			</tr>		
<?php
}
?>
    		</tbody>
			</table>
		</form>
		<?php echo $paginate_links; ?>
	</div>
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
