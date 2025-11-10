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
		<th rowspan="3" class="sized"></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<div id="content-table-inner">
		
<div id="table-content">
			<h2>Welkom bij Coop & Co</h2>

	<div id="table-content">
		
		<br class="clear">
		
		<form id="qrevents-tekst" action="/index.php/users/index" method="post">
			<div>
				<label for="searchfor" class="label-searchfor">ZZP-er zoeken: </label><input class="zzp-search-inp" type="text" name="searchfor" id="searchfor" value="<?php echo $searchfor; ?>" />
				<button>Zoek</button>
				<button onclick="document.getElementById('searchfor').value='';">Opnieuw</button>
			</div>
		</form>

	
		<br class="clear">
		<table id="jqGridAdmin"></table>
		<div id="jqGridAdminPager"></div>
		<script type="text/javascript" src="/js/grid_20170823.js"></script>
		<!--<script type="text/javascript" src="/js/grid.js"></script>-->	  
		<!--<script type="text/javascript" src="/js/grid_admin2.js">-->
		<br class="clear">
		
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