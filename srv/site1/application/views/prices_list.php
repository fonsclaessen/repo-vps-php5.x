<?php $this->load->view('header');?>
<div class="content">
<h1><?php echo $deelnemer;?></h1>
<?php if ($results->num_rows == 0) {
	echo "<p>Geen prijzen gevonden voor deze deelnemer.</p>";
}
else {
?>	

<?php echo $paginate_links;?>
<!-- <form id="prices_edit" action="/index.php/prices/edit" method="post"> -->

<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="/images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>

		<th rowspan="3" class="sized"><img src="/images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>

		<div id="content-table-inner">
		

		<div id="table-content">



				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Volgnummer</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Omschrijving</a></th>
					<th class="table-header-repeat line-left"><a href="">Tekst</a></th>

					<th class="table-header-repeat line-left"><a href="">Image</a></th>
					<th class="table-header-repeat line-left"><a href="">Teller</a></th>
					<th class="table-header-repeat line-left"><a href="">X prijs</a></th>
					<th class="table-header-repeat line-left"><a href="">Datum</a></th>
					<th class="table-header-repeat line-left"><a href="">Actief</a></th>
					<th class="table-header-options line-left" width="15px"><a href="">Edit</a></th>

					
				</tr>
<?php foreach ($results->result() as $row) { ?>				
				<tr>
					<td align="center"><?php echo $row->volgnummer; ?></td>
					<td><textarea id="price_omschrijving"  maxlength="400" rows="2" cols="30" name="price_omschrijving"><?php echo $row->omschrijving;?></textarea></td>

					<td><textarea align="left" id="price_text" class="imput small" maxlength="400" rows="2" cols="30" name="price_text"><?php echo $row->tekst;?></textarea></td>
					<td><img src="/image_price/<?php echo $deelnemer . "/" . $row->image; ?>" width=100 height=60/></td>
					<td><?php echo $row->trigger_counter; ?></td>
					<td><?php echo $row->winning_counter; ?></td>
					<td><?php echo $row->created_at; ?></td>
					<td><?php echo $row->actief; ?></td>
					<td class="options-width">
					<a href="/index.php/prices/edit/<?php echo $row->id;?>/<?php echo $page_number;?>/0" title="Edit" class="icon-1 info-tooltip"></a>

					</td>
				
				</tr>
<?php } ?>


				</table>


			</div>


	
			
			<div class="clear"></div>
		 
		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>

		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>

<!--tot hier aan toe -->




<!-- </form>-->
<p><?php echo $paginate_links;?></p>
<?php }?>
</div> 
<?php $this->load->view('footer');?>

