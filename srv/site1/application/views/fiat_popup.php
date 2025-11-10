<div class="content">
<style type="text/css">
.details_kop {
    zzzcolor: white;
	zzzpostition : absolute;
    float: left;
    font-size: 25px;
	font-weight: bold; 
    margin: 0px 0 0 0px;
}
.details {
	position: absolute;
	left: 45px;
	font-family: Arial, Helvetica, sans-serif;
}

.table_container {
	display: table;
	width: 830px;
}

.table_row {
	display: table-row;
}

.table_left {
	display: table-cell;
	width: 290px;
}
.table_right {
	display: table-cell;
	width: 290px;
}

.table_middle {
	display: table-cell;
	width: 50px;
}
.divlabel {
	width:180px;
	float:left;
}
.diveuro {
	float:left;
}
.divmoney {
	float:right;
	halign:right;
}

</style>
<div class="details">
<?php if ($factuur_details->num_rows==0) { ?>
<h1>Geen gegevens gevonden.</h1>
<?php } else {
	$factuur_rows = $factuur_details->result();
	$factuur_row = $factuur_rows[0];
?>
<br>
<div class="details_kop">Fiatteren van de uren en onkosten:</div>
<br>
<br>
<br>
<?php } ?>

			<table border="0" ZZwidth="100%" cellpadding="0" cellspacing="0"	id="product-table">
			<tbody>
			<tr class='table-row-odd'>
				<td width="160px">Project</td>
				<td width="200px"><strong><?php echo $factuur_row->projectnr;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Week</td>
				<td width="200px"><strong><?php echo $factuur_row->week;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>			
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Jaar</td>
				<td width="200px"><strong><?php echo $factuur_row->jaar;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Naam</td>
				<td width="200px"><strong><?php echo $factuur_row->company_name;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td width="160px"><?php echo dagnummer_naar_dagnaam($factuur_row->dagvanweek);?></td>
				<td width="200px"><strong><?php echo $factuur_row->dedatum;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>

			<tr class='table-row-odd'>
				<td width="160px">Van</td>
				<td width="200px"><strong><?php echo $factuur_row->start1;?></strong></td>
				<td width="100px">Tot</td>
				<td width="200px"><strong><?php echo $factuur_row->eind1;?></strong></td>
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Van</td>
				<td width="200px"><strong><?php echo $factuur_row->start2;?></strong></td>
				<td width="100px">Tot</td>
				<td width="200px"><strong><?php echo $factuur_row->eind2;?></strong></td>
			</tr>

			<tr class='table-row-odd'>
				<td width="160px">Totaal Uren</td>
				<td width="200px"><strong><?php echo formaturen_uit_minuten_totaal($factuur_row->totaal);?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>

			<tr class='table-row-odd'>
				<td width="160px">Opmerkingen</td>
				<td width="200px">
					<textarea rows="4" cols="30" style="height:80px;" id="invoer_opmerking" name="invoer_opmerking" readonly=readonly><?php echo $factuur_row->opmerkingen;?></textarea>
				</td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>			
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Opm. onkosten</td>
				<td width="200px">
					<textarea rows="4" cols="30" style="height:80px;" id="invoer_opmerking" name="invoer_opmerking" readonly=readonly><?php echo $factuur_row->onkosten;?></textarea>
				</td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>				
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Onkosten</td>
				<td width="200px"><strong><?php echo $factuur_row->onkostenbedrag;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>			
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Kilometers</td>
				<td width="200px"><strong><?php echo $factuur_row->km;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td width="160px"><label for="cbfiatteren">Fiatteren</label><input type="checkbox" id="cbfiatteren" name="cbfiatteren" /></td>
				<td width="200px">&nbsp;</td>					
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td colspan="4">&nbsp;</td>					
			</tr>
			<tr class='table-row-odd'>
				<td width="160px"><button>Opslaan</button></td>
				<td width="200px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			</tbody>
		</table>			

</div>
 

	
<!--
				<label for="invoer_opmerking"><strong>Opmerking</strong></label>
				<textarea rows="4" cols="30" style="height:80px;" id="invoer_opmerking" name="invoer_opmerking" readonly=readonly><?php echo $opmerkingen;?></textarea>
-->