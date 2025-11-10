<!--start-->
<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" title="default" />
<div class="content">

<!--<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" title="default" />-->

<style type="text/css">

button.css3button {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #050505;
	padding: 10px 20px;
	background: -moz-linear-gradient(
		top,
		#ffffff 0%,
		#ebebeb 50%,
		#dbdbdb 50%,
		#b5b5b5);
	background: -webkit-gradient(
		linear, left top, left bottom, 
		from(#ffffff),
		color-stop(0.50, #ebebeb),
		color-stop(0.50, #dbdbdb),
		to(#b5b5b5));
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	border: 1px solid #949494;
	-moz-box-shadow:
		0px 1px 3px rgba(000,000,000,0),
		inset 0px 0px 2px rgba(255,255,255,0);
	-webkit-box-shadow:
		0px 1px 3px rgba(000,000,000,0),
		inset 0px 0px 2px rgba(255,255,255,0);
	box-shadow:
		0px 1px 3px rgba(000,000,000,0),
		inset 0px 0px 2px rgba(255,255,255,0);
	text-shadow:
		0px 0px 0px rgba(000,000,000,0),
		0px 0px 0px rgba(255,255,255,0);
}

label	{  color: #999999; !important	}

.details_kop {
    zzzcolor: white;
	postition : absolute;
	top: 50px;
    float: left;
    font-size: 25px;
	font-weight: bold; 
    margin: 0px 0 0 0px;
}
.details {
	position: absolute;
	left: 25px;
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

.table-header-repeat {
line-height: 2; !important
padding: 0, 0, 0, 2; !important
}
</style>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script>
$(document).ready(function() {

   $('.radioButtonFiat').click(function() {
      var checkboxstate = $(this).is( ':checked' );  //dit is de omgekeerde waarde van wat er op scherm te zien was voor de klik, daarom werkt het als toggle
      $('.radioButtonFiat').attr("checked", false);
	  $(this).attr("checked", checkboxstate);
   });

	$('#closebutton').click(function() {
		parent.$.fancybox.close();
	})
});

function ajaxSaveFiatteringWeek() {
	var checkboxstateFiatteren = $("#cbfiatteren").is( ':checked' );  
	var checkboxstateAfkeuren  = $("#cbafkeuren").is( ':checked' );  
	var textredenAfkeuren = $('textarea#invoer_reden').val();
	var cb_checkboxstateFiatteren = 'N';
    var cb_checkboxstateAfkeuren  = 'N';
    if (checkboxstateFiatteren) {
        cb_checkboxstateFiatteren = 'J';
    }
    if (checkboxstateAfkeuren) {
        cb_checkboxstateAfkeuren = 'J';
    }
    var werkbriefje_id = $('#werkbriefje_id').val();
    $.post("/index.php/pdf/ajaxsavefiatpopup_weekjson",
		{
			req_werkbriefje_id: werkbriefje_id,
			req_fiat_checkbox: cb_checkboxstateFiatteren,
			req_afgekeurd_checkbox: cb_checkboxstateAfkeuren,
			req_redenafgekeurd_text:textredenAfkeuren
		},
		function (resp, resultaat) {
			if (resp.errorr == "succes") {
			    $("#opslaanmelding1").show(500);
				$("#opslaanmelding2").show(1500);								
		    } else {
				alert("De gegevens zijn niet opgeslagen. \r\n Mogelijk is het werkbriefje niet (meer) gefiatteerd door ZZP-er");
			}
		},
		'json'
		);
    return true;
}
</script>
<div class="details">


<?php if ( ($aantal_rows==0) || ($factuur_details_week->num_rows==0)) { ?>
<h1>Geen gegevens gevonden.</h1>
<h1>Ververs het scherm.</h1>
<?php } else {
	$factuur_rows = $factuur_details_week->result();
	$factuur_row = $factuur_rows[0];
	$fiatterenchecked = ($factuur_row->fiat_opdrachtgever == "J" ? 'checked="checked"' : '');
	$afgekeurdchecked = ($factuur_row->afgekeurd_opdrachtgever == "J" ? 'checked="checked"' : '');
	$redenafgekeurd = $factuur_row->afgekeurd_reden;
	
	
	
	$declareer_km = $factuur_row->declareer_km;
	$toon_km_declareren =  ($declareer_km == "J" ? true : false);
	
	$toon_km_declareren =  true;  //aangepast  km_declarabel
	
	$werkbriefje_id = $factuur_row->id;  
?>


<h1>&nbsp;</h1>
<h1>Fiatteren van de uren en onkosten:</h1>
<h1>&nbsp;</h1>

<?php     //hier stond  de } close bracket van de niet gevonden, nu helemaal onderaan!
		$totaal_uren = 0;
		$totaal_onkostenbedrag = 0.0;
		$totaal_km = 0;
		$totaal_km_declarabel = 0;
		
?>
			<input type="hidden" id="werkbriefje_id" name="werkbriefje_id" value="<?php echo $werkbriefje_id;?>"/>
			
			<table border="0" width="50%" cellpadding="0" cellspacing="0" id="project-table">
			<tbody>
			<tr class='table-row-odd'>
				<td width="160px">Naam</td>
				<td width="200px"><strong><?php echo $factuur_row->company_name;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Project</td>
				<td width="200px"><strong><?php echo $factuur_row->projectnr;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			
			<tr class='table-row-odd'>
				<td width="160px">Locatie</td>
				<td width="200px"><strong><?php echo $factuur_row->locatie;?></strong></td>
				<td width="100px">&nbsp;</td>
				<td width="100px">&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td width="160px">Afdeling</td>
				<td width="200px"><strong><?php echo $factuur_row->afdeling;?></strong></td>
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
			</tbody>
			</table>

<h1>&nbsp;</h1>
<!--<h1>&nbsp;</h1>-->

<?php $padding_grid ="padding:10px 5px 10px 5px;"; ?>
<?php $padding_grid_detail ="padding:0px 5px 0px 5px;"; ?>

			<table border="0" width="100%" cellpadding="0" cellspacing="0" id="perdag-table">
			<!--<tr><td colspan="8"><hr/></td></tr>-->
			<tr>
				<th class="table-header-repeat line-left" style="text-align:left; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Dag</th>
				<th class="table-header-repeat line-left" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Van</th>
				<th class="table-header-repeat line-left" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Tot</th>
				<th class="table-header-repeat line-left" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Van</th>
				<th class="table-header-repeat line-left" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Tot</th>
				<th class="table-header-repeat line-left" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Uren</th>
				<th class="table-header-repeat line-left" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Onkosten</th>
				<?php if ($toon_km_declareren) { ?>
					<th class="table-header-repeat line-left zzzline-right" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Km</th>
					<th class="table-header-repeat line-left zzzline-right" style="text-align:right; color:#ffffff; font-size:14px; <?php echo $padding_grid;?>">Km declarabel</th>
				<?php } ?>
			</tr>
			<tr><td colspan="8"><hr/></td></tr>
			<tbody>
			
			<?php if ($factuur_details_week_dagen->num_rows==0) { ?>
				<h1>Geen gegevens per dag gevonden.</h1>
<?php } else {
	
	foreach ($factuur_details_week_dagen->result() as $row_perdag ) { 
		$totaal_uren += $row_perdag->totaal;
		$totaal_onkostenbedrag += $row_perdag->onkostenbedrag;
		$totaal_km += $row_perdag->km;
		$totaal_km_declarabel += $row_perdag->km_declarabel;
?>
<tr class='table-row-odd'>
			<td width="100px" style="text-align:left; <?php echo $padding_grid_detail;?>"><strong><?php echo dagnummer_naar_dagnaam($row_perdag->dagvanweek);?></strong></td>
			<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $row_perdag->start1;?></strong></td>
			<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $row_perdag->eind1;?></strong></td>
			<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $row_perdag->start2;?></strong></td>
			<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $row_perdag->eind2;?></strong></td>
			<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo formaturen_uit_minuten_totaal($row_perdag->totaal);?></strong></td>
			<td width="94px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $row_perdag->onkostenbedrag;?></strong></td>
			<?php if ($toon_km_declareren) { ?>
				<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $row_perdag->km;?></strong></td>
				<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $row_perdag->km_declarabel;?></strong></td>
			<?php } ?>
		</tr>
<?php
	}
}

?>
 			<tr class='table-row-odd'>
				<td width="100px">&nbsp;</td>
				<td width="85px">&nbsp;</td>
				<td width="85px">&nbsp;</td>
				<td width="85px">&nbsp;</td>
				<td width="85px"><hr></td>
				<td width="85px"><hr></td>
				<td width="94px"><hr></td>
				<?php if ($toon_km_declareren) { ?>
					<td width="85px"><hr></td>
					<td width="85px"><hr></td>
				<?php } ?>
			</tr>			
			<tr class='table-row-odd'>
				<td width="100px">&nbsp;</td>
				<td width="85px">&nbsp;</td>
				<td width="85px">&nbsp;</td>				
				<td width="85px">&nbsp;</td>
				<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>">Totalen:</td>
				<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo formaturen_uit_minuten_totaal($totaal_uren);?></strong></td>
				<td width="94px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $totaal_onkostenbedrag;?></strong></td>
				<?php if ($toon_km_declareren) { ?>
					<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $totaal_km;?></strong></td>
					<td width="85px" style="text-align:right; <?php echo $padding_grid_detail;?>"><strong><?php echo $totaal_km_declarabel;?></strong></td>					
				<?php } ?>
			</tr>			
			
			</tbody>
			</table>
			
			
			<table border="0" width="100%" cellpadding="0" cellspacing="0" id="totalen-table">
			<tr class='table-row-odd'>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class='table-row-odd'>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr class='table-row-odd'>
				<td width="50px"><label for="cbfiatteren">Fiatteren</label><input class="radioButtonFiat" type="checkbox" id="cbfiatteren" name="cbfiatteren" <?php echo $fiatterenchecked; ?> /></td>
				<td width="50px"><label for="cbafkeuren">Afkeuren</label><input class="radioButtonFiat" type="checkbox" id="cbafkeuren" name="cbafkeuren" <?php echo $afgekeurdchecked; ?>/></td>				
			</tr>
			
			<tr class='table-row-odd' colspan="2">
				<td width="100px">&nbsp;</td>
			</tr>
			
			<tr class='table-row-odd'>
				<td width="50px" colspan="3">
					<label for="invoer_reden" >Reden van afkeuren</label>
					<br>
					<textarea rows="5" cols="90" id="invoer_reden" name="invoer_reden" ><?php echo $redenafgekeurd;?></textarea>
</td>
</tr>

<tr class='table-row-odd'>
<td colspan="2">&nbsp;</td>					
</tr>

<tr class='table-row-odd'>
<td width="140px">
<button type="button" value="" class="css3button" onclick="ajaxSaveFiatteringWeek();">Opslaan</button>
</td>
<td>
<button type="button" name="closebutton" id="closebutton" value="" class="css3button">Afsluiten</button>
</td>
</tr>
<tr class='table-row-odd'>
<td colspan="2">&nbsp;</td>					
</tr>
<tr class='table-row-odd'>
<td colspan="2" width="160px">
<div id="opslaanmelding"><strong><span id="opslaanmelding1" style="display: none;">De gegevens zijn opgeslagen.&nbsp;&nbsp;</span><span id="opslaanmelding2" style="display: none; color:red;">Het scherm kan afgesloten worden.</span></div></strong>
</td>
</tr>

</tbody>
</table>
<?php } ?>
</div>