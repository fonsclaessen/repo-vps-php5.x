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
 
	<br class="clear">
	<h1>Werkbriefjes fiatteren</h1>
	<div style="float: right;">
	<label for="cbfilterfiat">Gefiatteerde of afgekeurde werkbriefjes niet tonen: </label><input type="checkbox" id="cbfilterfiat" name="cbfilterfiat" <?php echo ($filterfiat == "1" ? 'checked="checked"' : ''); ?>/>
	</div>
	<?php echo $paginate_links; ?>
	<div>&nbsp;</div>
		<form id="qrevents-tekst" action="/index.php/texts/edit" method="post">
			<table border="0" width="100%" cellpadding="0" cellspacing="0"	id="product-table">
			<tr>
				<th class="table-header-repeat line-left"><a href="">Week</a></th>
				<th class="table-header-repeat line-left"><a href="">Jaar</a></th>
				<th class="table-header-repeat line-left"><a href="">Zzp naam</a></th>
				<th class="table-header-repeat line-left ZZZzzp_t_datum"><a href="">Project</a></th>
				<th class="table-header-repeat line-left ZZZzzp_t_datum"><a href="">Werkbriefje</a></th>
				<th class="table-header-repeat line-left ZZZzzp_t_datum"><a href="">Fiat</a></th>
				<th class="table-header-repeat line-left ZZZzzp_t_datum"><a href="">Afgekeurd</a></th>
				<th class="table-header-repeat line-left zzp_t_datum"><a href="">Fiatteren</a></th>
			</tr>

<style>
.t1 {
	width:5.0%;    /* week */
}
.t2 {
	width:5.0%;   /* jaar */
}
.t3 {
	width:30.0%;  /* zzpnaam */
}
.t4 {
	width:33.0%;  /* project */
}
.t5 {
	width:8.0%;  /* Werkbriefje */
}
.t6 {
	width:6.0%;  /* fiat */
}
.t7 {
	width:7.0%;  /* Afgekeurd */
}
.t8 {
	width:6.0%;  /* Fiatteren */
}
</style>
			<tbody>
<?php

foreach ( $results->result() as $row ) {
?>
	<tr class='table-row-odd'>
				<td align="left" valign="top" class="td-padding t1"><div><?php echo $row->week; ?></div></td>
				<td align="left" valign="top" class="td-padding t2"><div><?php echo $row->jaar; ?></div></td>
				<td align="left" valign="top" class="td-padding t3"><div><?php echo $row->company_name; ?></div></td>
				<td align="left" valign="top" class="td-padding t4"><div><?php echo $row->locatie; ?></div></td>
				<td align="left" valign="top" class="td-padding t5"><div><?php echo $row->id; ?></div></td>
				<td align="left" valign="top" class="td-padding t6"><div><?php echo ($row->fiat_opdrachtgever == "J" ? "Ja" : "Nee"); ?></div></td>
				<td align="left" valign="top" class="td-padding t7"><div><?php echo ($row->afgekeurd_opdrachtgever == "J" ? "Ja" : "Nee"); ?></div></td>
				<td ZZZalign="right" ZZZclass="td-padding" ZZZvalign="top" class=t8"">
				<button class="fiat_popup fancybox.iframe a_pdfpopup" href="/index.php/pdf/fiatpopup_week/<?php echo $row->id; ?>">Fiatteren</button>
				</td>
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
