<?php
$this->load->view ( 'header' );
?>
<!--   -->
<style>
.k1 {
	width:4.0%;   
}
.k2 {
	width:20.0%;   
}
.k3 {
	width:4.0%; 
}
.k4 {
	width:7.0%; 
}
.k5 {
	width:1.0%; 
}
.k6 {
	width:5.0%; 
}
.k7 {
	width:5.0%; 
}
.k8 {
	width:5.0%; 
}
.k9 {
	width:6.0%; 
}
.k10 {
	width:6.0%; 
}
.k11 {
	width:6.0%; 
}
.k12 {
	width:6.0%; 
}
.k13 {
	width:6.0%; 
}
.k14 {
	width:6.0%; 
}
.k15 {
	width:8.0%; 
}
.k16 {
	width:1.0%; 
}
</style>
<!-- -->

<!-- div class="content">-->

<h1><?php echo "" . $company_name; ?></h1>
<!--<p>&nbsp;</p>-->
<div>&nbsp;</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0"	id="content-table">
	<tr>
		<!--<th rowspan="3" class="sized"><img src="/images/shared/side_shadowleft.jpg" width="20" height="300"	alt="" /></th>-->
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<!--<th rowspan="3" class="sized"><img src="/images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>-->
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>

		<div id="content-table-inner">

		<div id="table-content">
		<div>
	
		<div style="float: left;"><button class="popup_week fancybox.iframe a_pdfpopupweek" href="/index.php/pdf/pdfpopupweek">Overzicht uren per week</button></div>
		<div style="float: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		<div style="float: left;"><button class="prevnextyearknupke" ONCLICK="window.location.href='/index.php/pdf/prevyear'"><</button></div>	
		<div style="float: left;" class="prevnextyear"><h1 class="prevnextyear">&nbsp;<?php echo $jaartal_filtered; ?>&nbsp;</h1></div>
		<div style="float: left;"><button class="prevnextyearknupke" ONCLICK="window.location.href='/index.php/pdf/nextyear'">></button></div>	

	</div>
	<br class="clear">
	<br class="clear">
	
	<?php echo $paginate_links; ?>
	<div>&nbsp;</div>
		<form id="qrevents-tekst" action="/index.php/texts/edit" method="post">
			<table border="0" width="100%" cellpadding="0" cellspacing="0"	id="product-table">
			<tr>
				<th class="table-header-repeat line-left"><a href="">Week</a></th>			
				<th class="table-header-repeat line-left minwidth-1 zzp_t_naam"><a>&nbsp;</a></th>
				<th class="table-header-repeat line-left zzp_t_uren"><a>Uren</a></th>				
				<th class="table-header-repeat line-left zzp_t_datum"><a>Factuur</a></th>
				<th class="table-header-options line-left"><a>&nbsp;</a></th>
				<th class="table-header-repeat line-left zzp_t_datum"><a>Factuur</a></th>
				<th class="table-header-repeat line-left zzp_t_datum"><a>Vervalt</a></th>
				<th class="table-header-repeat line-left zzp_t_datum"><a>Betaald</a></th>
	  			<th class="table-header-repeat line-left zzp_t_bedrag"><a>Factuur</a></th>
				<th class="table-header-repeat line-left zzp_t_bedrag"><a>Voorschot</a></th>
				<th class="table-header-repeat line-left zzp_t_bedrag"><a>Borg</a></th>
				<th class="table-header-repeat line-left zzp_t_bedrag"><a>Kosten</a></th>
				<th class="table-header-repeat line-left zzp_t_bedrag"><a>Resteert</a></th>
				<th class="table-header-repeat line-left zzp_t_bedrag"><a>BTW</a></th>
				<th class="table-header-repeat line-left"><a>Factuur ADZ</a></th>
				<th class="table-header-options line-left"><a>&nbsp;</a></th>
			</tr>

			<tbody>
<?php
foreach ( $results->result() as $row ) {
?>
	<tr class='table-row-odd'>
				<td align="left" class="k1 td-padding" valign="top"><?php echo $row->Week; ?></td>
				<td align="left" class="k2 td-padding " valign="top" ><div><?php echo $row->Opdrachtgever; ?></div></td>
				<td align="right" class="k3 td-padding" valign="top"><?php echo formaturen($row->uren); ?></td>
				<td align="left" class="k4 td-padding" valign="top"><?php echo $row->Factuurnr; ?></td>
				<td class="k5 options-width"><div class="div-icon-pdf2" onclick="ajaxPdf(<?php echo $row->id; ?>)">&nbsp;</div></td>
				<td align="left" class="k6 td-padding" valign="top"><?php echo $row->FactuurDatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->FactuurDatumDATEONLY)); ?></td>
				<td align="left" class="k7 td-padding" valign="top"><?php echo $row->VervalDatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->VervalDatumDATEONLY)); ?></td>
				<td align="left" class="k8 td-padding" valign="top"><?php echo $row->BetaalddatumDATEONLY===null ? "&nbsp;" : date('d-m-y', strtotime($row->BetaalddatumDATEONLY)); ?></td>
				<td align="right" class="k9 td-padding" valign="top"><?php echo number_format($row->FactuurTotaal, 2, ',', '.'); ?></td>
				<td align="right" class="k10 td-padding" valign="top"><?php	echo number_format($row->Voorschot, 2, ',', '.'); ?></td>
				<td align="right" class="k11 td-padding" valign="top"><?php echo number_format($row->BorgZZP, 2, ',', '.'); ?></td>
				<td align="right" class="k12 td-padding" valign="top"><a class="various fancybox.iframe a_pdfpopup" href="/index.php/pdf/pdfpopup/<?php echo $row->Factuurnr; ?>"><?php echo number_format($row->kosten, 2, ',', '.'); ?></a></td>
				<td align="right" class="k13 td-padding" valign="top"><?php echo number_format($row->Resteert, 2, ',', '.'); ?></td>
				
				<?php if ($btw_tonen) { ?>
					<td align="right" class="k14 td-padding" valign="top"><?php echo number_format($row->BtwSaldo, 2, ',', '.'); ?></td><!--BTW-->
				<?php } else {?>
					<td align="right" class="k14 td-padding" valign="top"><?php echo number_format(0.0, 2, ',', '.'); ?></td><!--BTW-->
				<?php }?>

				<td align="left" class="k15 td-padding" valign="top"><?php echo $row->FactuurADZ; ?></td>
				<td class="k16 options-width">
				<?php if (trim($row->FactuurADZ) != "") {?>
					<div class="div-icon-pdf2" onclick="ajaxPdfAdz(<?php echo $row->FactuurADZ; ?>)">
				<?php } else { ?>
					<div>
				<?php } ?>
				&nbsp;</div></td>
			</tr>		
<?php
}
?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>				
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right" class="td-padding" valign="top"><hr class="hrtotal" /></td>
				<td align="right" class="td-padding" valign="top"><hr class="hrtotal" /></td>
				<td align="right" class="td-padding" valign="top"><hr class="hrtotal" /></td>
				<td align="right" class="td-padding" valign="top"><hr class="hrtotal" /></td>
				<td align="right" class="td-padding" valign="top"><hr class="hrtotal" /></td>
				<td align="right" class="td-padding" valign="top"><hr class="hrtotal" /></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
<?php foreach ( $result_total->result () as $row_total ) { ?>
			<tr>
				<td>Totaal:</td>
				<td>&nbsp;</td>
				<td align="right" class="td-padding" valign="top"><?php echo formaturen_uit_minuten_totaal($uren_in_minuten_totaal); ?></td>
				<td>&nbsp;</td>				
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				
				<td align="right" class="td-padding" valign="top"><?php echo number_format($row_total->SUMFactuurTotaal, 2, ',', '.'); ?></td>
				<td align="right" class="td-padding" valign="top"><?php echo number_format($row_total->SUMVoorschot, 2, ',', '.'); ?></td>
				<td align="right" class="td-padding" valign="top"><?php echo number_format($row_total->SUMBorgZZP, 2, ',', '.'); ?></td>
				<td align="right" class="td-padding" valign="top"><?php echo number_format($row_total->totaal_kosten, 2, ',', '.'); ?></td>			
				<td align="right" class="td-padding" valign="top"><?php echo number_format($row_total->SUMResteert, 2, ',', '.'); ?></td>
				<?php if ($btw_tonen) { ?>
					<td align="right" class="td-padding" valign="top"><?php echo number_format($row_total->totaal_btwsaldo, 2, ',', '.'); ?></td>
				<?php } else {?>
					<td align="right" class="td-padding" valign="top"><?php echo number_format(0.0, 2, ',', '.'); ?></td>
				<?php }?>
				<td align="right" class="td-padding" valign="top"><?php //echo "---"; ?></td>
				<td>&nbsp;</td>
				<!--<td>&nbsp;</td>-->
			</tr>
<?php }?>

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
