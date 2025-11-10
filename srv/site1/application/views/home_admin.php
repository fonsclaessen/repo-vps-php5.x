<?php
if (1==2) {
	$this->load->view ( 'header' );
} else {
	$this->load->view ( 'header_grid' );
}
?>
	<div id="page-heading">
	<h3>Aangemeld als Administrator</h3>
	</div>

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><!--<img src="/images/shared/side_shadowleft.jpg" width="20" height="300" alt="" />--></th>
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
			<h2>Welkom bij Coop & Co</h2>

	<div id="table-content">
	
		<br class="clear">
		<table id="jqGridAdmin"></table>
		<div id="jqGridAdminPager"></div>
		<script type="text/javascript" src="/js/grid_20170823.js"></script>	  
	
	<div>&nbsp;</div>
		<form id="qrevents-tekst" action="/index.php/users/index" method="post">
		
			<div>
				<label for="searchfor" class="label-searchfor">ZZP-er zoeken: </label><input class="zzp-search-inp" type="text" name="searchfor" id="searchfor" value="<?php echo $searchfor; ?>" />
				<button>Zoek</button>
				<button onclick="document.getElementById('searchfor').value='';">Opnieuw</button>
			</div>

	<?php echo $paginate_links; ?>
	<br/>

			<table border="0" width="100%" cellpadding="0" cellspacing="0"	id="product-table">
			<tr>
				<th class="table-header-repeat line-left"><a href="">Gebruiker ID</a></th>				
				<th class="table-header-repeat line-left"><a href="">Gebruiker</a></th>
				<th class="table-header-options line-left"><a href="">Naam</a></th>
				<th class="table-header-repeat line-left"><a href="">E-mail</a></th>
				<th class="table-header-repeat line-left"><a href="">Bedrijfsnaam</a></th>
			</tr>

			<tbody>
<?php
foreach ( $results->result() as $row ) {
	?>
			<tr class='table-row-odd'>
				<td align="left" class="td-padding" valign="top"><a href="/index.php/users/adminselect/<?php echo $row->GebruikerID; ?>"><?php echo $row->GebruikerID; ?></a></td>
				<td align="left" valign="top" class="td-padding"><?php echo $row->username; ?></td>
				<td align="left" class="td-padding" valign="top"><?php echo $row->last_name; ?></td>	
				<td align="left" class="td-padding" valign="top"><?php echo $row->email; ?></td>
				<td align="left" class="td-padding" valign="top"><?php echo $row->company_name; ?></td>
			</tr>		
<?php
}
?>

    		</tbody>
			</table>
		</form>
		<?php echo $paginate_links; ?>
	</div>

<!-- tableend -->			
			
			
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
	<div class="clear">&nbsp;</div>


<br class="clear" />
<?php $this->load->view('footer');?>
