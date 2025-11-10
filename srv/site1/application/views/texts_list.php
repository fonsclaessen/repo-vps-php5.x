<?php
$this->load->view ( 'header' );
?>
<div class="content">
<h1><?php
echo "Welkom " . $company_name;
?></h1>
<h1><?php
echo "Tekst " . ($moderated === TRUE ? "moderatie" : "beheer");
?></h1>

<?php
if ($moderated === FALSE) {
	?>
<p>&nbsp;</p>
<button style='color: black;'
	onClick="location.href='/index.php/texts/add/<?php
	echo $page_number;
	?>'">Nieuwe
Tekst</button>
<!-- <p><a href="/index.php/texts/add/<?php
	//echo $page_number;
	?>"><input type="button" name="addtext" value="(3)Nieuwe Text" /></a></p>-->
<div>&nbsp;</div>
<?php
}
?>
<table border="0" width="100%" cellpadding="0" cellspacing="0"
	id="content-table">
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
<?php

echo $paginate_links;
?>
<form id="qrevents-tekst" action="/index.php/texts/edit" method="post">
		<table border="0" width="100%" cellpadding="0" cellspacing="0"
			id="product-table">
			<tr>
				<th class="table-header-repeat line-left minwidth-1"><a href="">Nr.</a></th>			
				<th class="table-header-repeat line-left minwidth-1"><a href="">Naam</a></th>
				<th class="table-header-repeat line-left minwidth-1"><a href="">Plaats</a></th>
				<th class="table-header-repeat line-left"><a href="">Tekst</a></th>

				<th class="table-header-repeat line-left"><a href="">Moderated</a></th>
				<th class="table-header-repeat line-left"><a href="">Actief</a></th>
				<th class="table-header-repeat line-left"><a href="">Deelnemer</a></th>

				<th class="table-header-options line-left" width="15px"><a href="">Edit</a></th>
				<!--<th class="table-header-options line-left" width="15px"><a href="">Twitter</a></th>-->
<?php if (!($moderated === TRUE)) : ?>				
				<th class="table-header-options line-left" width="15px"><a href="">Delete</a></th>
<?php endif ?>				
			</tr>

			<tbody>
<?php
foreach ( $results->result () as $row ) {
	?>
	<tr class='table-row-odd'>
					<td align="left" valign="center" class="td-padding"><?php
	echo $row->id;
	?></td>
					<td align="left" valign="center" class="td-padding"><?php
	echo $row->naam_bezoeker;
	?></td>
					<td align="left" valign="center" class="td-padding"><?php
	echo $row->woonplaats_bezoeker;
	?></td>
					<td align="left" class="td-padding" valign="top"><textarea
						id="qoute_text" class="imput small valid" maxlength="400" rows="4"
						cols="64" name="quote_text"><?php
	echo $row->content_text;
	?></textarea></td>
					<td align="left" valign="center" class="td-padding"><?php
	echo ($row->moderated == "Y" ? "Ja" : "Nee");
	?></td>
					<td align="left" valign="center" class="td-padding"><?php
	echo $row->actief;
	?></td>
					<td align="left" valign="center" class="td-padding"><?php
	echo $row->deelnemer;
	?></td>
					<td class="options-width"><a
						href="/index.php/texts/edit/<?php
	echo $row->id;
	?>/<?php
	echo $page_number;
	?><?php
	echo ($moderated === TRUE ? '/moderated' : '')?>"
						class="icon-1 info-tooltip"></a></td>
			<!-- <td class="options-width"><a
					href="/index.php/texts/twitter/<?php
	//echo $row->id;
	?>/<?php
	//echo $page_number;
	?>"
					class="icon-1 info-tooltip"></a></td>-->
<?php if (!($moderated === TRUE)) : ?>					
					<td class="options-width"><a
						href="/index.php/texts/delete/<?php
	echo $row->id;
	?>/<?php
	echo $page_number;
	?><?php
	echo ($moderated === TRUE ? '/moderated' : '')?>"
						class="icon-1 info-tooltip"></a></td>
<?php endif ?>						
				</tr>		
<?php
}
?>
    </tbody>
		</table>
		</form>
		<p><?php
		echo $paginate_links;
		?></p>
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
<?php
$this->load->view ( 'footer' );
?>
