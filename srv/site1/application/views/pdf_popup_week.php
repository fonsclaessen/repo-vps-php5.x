<div class="content">
<style type="text/css">
.details_kop {
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
	/*width: 240px;*/
	width: 100px;
}

.table_row {
	display: table-row;
}

.table_left {
	display: table-cell;
	width: 90px;
}
.table_right {
	display: table-cell;
	width: 90px;
}

.divlabel {
	width:90px;
	float:left;
}
.divlabeluren {
	zzzwidth:90px;
	float:right;
}

.divuren {
	float:right;
	halign:right;
}


</style>

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


<div class="details">
<?php if ($result->num_rows==0) { ?>
<h1>Geen gegevens gevonden.</h1>
<?php } else {?>
<!--<br>
<div class="details_kop">Week totalen:</div>
<br>-->
<br>
<br>
<?php } ?>

<?php
/*if ($result->num_rows >0) {
	echo "<br/>" . $uren_in_minuten_totaal;
	echo "<br/>" . formaturen_uit_minuten_totaal($uren_in_minuten_totaal);
	
	foreach ( $result->result () as $row ) {
		echo "<br> week: " . $row->week;
		echo "   alleenuren: " . $row->alleenuren;
		echo "   alleenminuten: " . $row->alleenminuten;
		echo "   minuten: " . $row->alleenminuten;
		echo "   m: " . formaturen_uit_minuten_totaal($row->minuten);
	}
}*/
	
?>


<div class="table_container">
		<div class="table_row">
			<div class="table_left">
				<div class="divlabel"><strong>Week</strong></div>
			</div>
			<div class="table_right">
				<div class="divlabeluren"><strong>Uren</strong></div>
			</div>
		</div>
		
		<div class="table_row">
			<div class="table_left">
				<hr>
			</div>
			<div class="table_right">
				<hr>
			</div>
		</div>
    <?php 
if ($result->num_rows >0) {
	foreach ( $result->result () as $row ) {
	?>
    
		<div class="table_row">
			<div class="table_left">
				<div class="divweek"><?php echo $row->week; ?></div>
				
			</div>
			<div class="table_right">
				<div class="divuren"><?php echo formaturen_uit_minuten_totaal($row->minuten); ?></div>
			</div>
		</div>
<?php

	}
}
?>


		<div class="table_row">
			<div class="table_left">
			</div>
			<div class="table_right">
				<hr>
			</div>
		</div>
		
		<div class="table_row">
			<div ZZZclass="table_left">
				<div class="divuren"><strong>Totaal:&nbsp;&nbsp;</strong></div>
			</div>
			<div class="table_right">
				<div class="divuren"><strong><?php echo formaturen_uit_minuten_totaal($uren_in_minuten_totaal); ?></strong></div>
			</div>
		</div>

	</div>


</div>
</div>