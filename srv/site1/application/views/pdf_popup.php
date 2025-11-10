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

.table_container2 {
	display: table;
	width: 530px;
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

.table_left2 {
	display: table-cell;
	width: 160px;
}
.table_middle2 {
	display: table-cell;
	width: 60px;
}
.table_right2 {
	display: table-cell;
	width: 60px;
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

.divlabel2 {
	width:150px;
	float:left;
}
.diveuro2 {
	float:left;
}
.divmoney2 {
	float:right;
	halign:right;
	padding-right: 30px;
}


</style>
<div class="details">
<?php if ($factuur_details2->num_rows==0) { ?>
	<h1>Geen gegevens gevonden.</h1>
<?php } else {?>
	<br>
	<div class="details_kop">Specificatie van de inhoudingen:</div>
	<br>
	<br>
	<br>
<?php } ?>



<?php
if ($factuur_details2->num_rows >0) {
	
//	foreach ( $factuur_details2->result () as $row ) {  //aan het eind staat een break, het is dus maar 1 row.
//		$totaal_fee = mssql_number_bereken($row->Bemiddeling) + mssql_number_bereken($row->Administratie) +mssql_number_bereken($row->kostenvoorschot);
//		$totaal_btw=mssql_number_bereken($row->BemiddelingBTW) + mssql_number_bereken($row->AdministratieBTW) + mssql_number_bereken($row->kostenvoorschotbtw);
//		$totaal_totaal = $totaal_fee + $totaal_btw;
		
	foreach ( $factuur_details2->result () as $row ) {  //aan het eind staat een break, het is dus maar 1 row.
		$totaal_fee = mssql_number_bereken($row->Bemiddeling) + mssql_number_bereken($row->Administratie) + mssql_number_bereken($row->kostenvoorschot) + mssql_number_bereken($row->factoringkosten);
		$totaal_btw=mssql_number_bereken($row->BemiddelingBTW) + mssql_number_bereken($row->AdministratieBTW) + mssql_number_bereken($row->kostenvoorschotbtw) + mssql_number_bereken($row->factoringbtw);
		$totaal_totaal = $totaal_fee + $totaal_btw;
		
		
?>
<BR>
<div class="table_container2">

<div class="table_row">

<div class="table_left2">
<div class="zzzdivlabel"><strong></strong></div>
</div>

<div class="table_middle2">
<div class="zzzdivlabel"><strong>Fee</strong></div>
</div>

<div class="table_right2">
<div class="zzzdivlabel"><strong>BTW</strong></div>
</div>
<div class="table_right2">
<div class="zzzdivlabel"><strong>Totaal</strong></div>
</div>
</div>

<div class="table_row">
<div class="table_left2">
<hr>
</div>
<div class="table_middle2">
<hr>
</div>
<div class="table_right2">
<hr>
</div>
<div class="table_right2">
<hr>
</div>
</div>

<div class="table_row">
<div class="table_left2">
<div class="divlabel2">Servicefee 10%</div>
</div>
<div class="table_middle2">
<div class="diveuro2"><strong>&euro;</strong></div>
			<div class="divmoney2"><strong><?php echo mssql_number_format($row->Bemiddeling, 2, ',', '.'); ?></strong></div>
</div>
<div class="table_right2">
<div class="diveuro2"><strong>&euro;</strong></div>
			<div class="divmoney2"><strong><?php echo mssql_number_format($row->BemiddelingBTW, 2, ',', '.'); ?></strong></div>
</div>
<div class="table_right2">
<div class="diveuro2"><strong>&euro;</strong></div>
<div class="divmoney2"><strong><?php echo mssql_number_format(mssql_number_bereken($row->Bemiddeling) + mssql_number_bereken($row->BemiddelingBTW), 2, ',', '.'); ?></strong></div>
</div>
</div>

<div class="table_row">
<div class="table_left2">
<div class="divlabel2">Administratiefee 5%</div>
</div>
<div class="table_middle2">
<div class="diveuro2"><strong>&euro;</strong></div>
			<div class="divmoney2"><strong><?php echo mssql_number_format($row->Administratie, 2, ',', '.'); ?></strong></div>
</div>

<div class="table_right2">
<div class="diveuro2"><strong>&euro;</strong></div>
			<div class="divmoney2"><strong><?php echo mssql_number_format($row->AdministratieBTW, 2, ',', '.'); ?></strong></div>
</div>
<div class="table_right2">
<div class="diveuro2"><strong>&euro;</strong></div>
			<div class="divmoney2"><strong><?php echo mssql_number_format(mssql_number_bereken($row->Administratie) + mssql_number_bereken($row->AdministratieBTW), 2, ',', '.'); ?></strong></div>
</div>
</div>

<div class="table_row">
	<div class="table_left2">
		<div class="divlabel2">Kosten voorschot</div>
	</div>
	<div class="table_middle2">
		<div class="diveuro2"><strong>&euro;</strong></div>
		<div class="divmoney2"><strong><?php echo mssql_number_format($row->kostenvoorschot, 2, ',', '.'); ?></strong></div>
	</div>
	<div class="table_right2">
		<div class="diveuro2"><strong>&euro;</strong></div>
		<div class="divmoney2"><strong><?php echo mssql_number_format($row->kostenvoorschotbtw, 2, ',', '.'); ?></strong></div>
	</div>
	<div class="table_right2">
		<div class="diveuro2"><strong>&euro;</strong></div>
		<div class="divmoney2"><strong>   <?php echo mssql_number_format(mssql_number_bereken($row->kostenvoorschot) + mssql_number_bereken($row->kostenvoorschotbtw), 2, ',', '.'); ?></strong></div>
	</div>
</div>

<div class="table_row">
	<div class="table_left2">
		<div class="divlabel2">Kosten factoring</div>
	</div>
	<div class="table_middle2">
		<div class="diveuro2"><strong>&euro;</strong></div>
		<div class="divmoney2"><strong>
		<?php echo mssql_number_format($row->factoringkosten, 2, ',', '.'); ?>
		</strong></div>
	</div>
	<div class="table_right2">
		<div class="diveuro2"><strong>&euro;</strong></div>
		<div class="divmoney2"><strong>
		<?php echo mssql_number_format($row->factoringbtw, 2, ',', '.'); ?>
		</strong></div>
	</div>
	<div class="table_right2">
		<div class="diveuro2"><strong>&euro;</strong></div>
		<div class="divmoney2"><strong>
		<?php echo mssql_number_format(mssql_number_bereken($row->factoringkosten) + mssql_number_bereken($row->factoringbtw), 2, ',', '.'); ?>
		</strong></div>
	</div>
</div>

<div class="table_row">
<div class="table_left2">
<hr>
</div>
<div class="table_middle2">
<hr>
</div>
<div class="table_right2">
<hr>
</div>
<div class="table_right2">
<hr>
</div>
</div>

<div class="table_row">
<div class="table_left2">
<div class="divlabel2">Totaal</div>
</div>
<div class="table_middle2">
<div class="diveuro2"><strong>&euro;</strong></div>
<div class="divmoney2"><strong>   <?php echo mssql_number_format($totaal_fee, 2, ',', '.'); ?></strong></div>


</div>
<div class="table_right2">
<div class="diveuro2"><strong>&euro;</strong></div>
			<div class="divmoney2"><strong>   <?php echo mssql_number_format($totaal_btw, 2, ',', '.'); ?></strong></div>
</div>
<div class="table_right2">
<div class="diveuro2"><strong>&euro;</strong></div>
			<div class="divmoney2"><strong>   <?php echo mssql_number_format($totaal_totaal, 2, ',', '.'); ?></strong></div>
</div>
</div>

</div>



<?php
break; //sql geeft mogelijk meerdere regels, maar met deze break gebruiken we alleen de eerste.
}
}
?>


</div>



</div>
