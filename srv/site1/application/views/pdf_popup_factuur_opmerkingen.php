<style type="text/css">


.divTable{
	display: table;
	width: 100%;
}
.divTableRow {
	display: table-row;
}
.ZZdivTableHeading {
	background-color: #EEE;
	display: table-header-group;
}
.divTableCell, .divTableHead {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
}


.divTableCell1 {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
	width: 90px;
	font-weight: bold;
}
.divTableCell2 {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
}

.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
	font-weight: bold;
}
.divTableFoot {
	background-color: #EEE;
	display: table-footer-group;
	font-weight: bold;
}
.divTableBody {
	display: table-row-group;
}

textarea {
  border: 1px solid black;
 // background: green;
 // color: white;
  padding: 5px;
  width: 90%;
  height: 350px;
  font-family: Arial, Helvetica, sans-serif;
  font: 16px Arial, Helvetica, ans-serif;
}

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
	width: 60px;
}
.table_middle2 {
	display: table-cell;
	width: 60px;
}
.table_right2 {
	display: table-cell;
	/* width: 560px; */
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
<div class="content">
<div class="detailsZZZ">

<?php 
	if (count($factuur_opmerkingen) == 0) {  ?>
<h1>Geen gegevens gevonden.</h1>
<?php
	} else {
?>	
<div class="divTable" style="border: 1px solid #000;" >
	<div class="divTableHeading">
	<div class="divTableHead">&nbsp;Datum</div>
	<div class="divTableHead">&nbsp;Contactmoment</div>
	</div>
	<div class="divTableBody">

<?php foreach ($factuur_opmerkingen as $opmerking) { ?>	
	<div class="divTableRow">
		<div class="divTableCell1">&nbsp;<?php echo $opmerking->datum; ?></div>
		<div class="divTableCell2">&nbsp;<?php echo $opmerking->omschr; ?></div>
	</div>
<?php } ?>
	</div>
</div>

<?php } ?>	
</div>

</div>
