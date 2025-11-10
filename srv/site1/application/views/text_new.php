<?php
$this->load->view ( 'header' );
?>

<div class="content">
<h1>Deelnemer:&nbsp;&nbsp;&nbsp;<?php
echo $deelnemer;
?></h1>
<br>
<form id="texts_edit" action="/index.php/texts/savenew" method="post">
<input type="hidden" name="text_page_hidden" value="<?php echo $page_number;?>">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
		<tr>
			<th valign="top">Tekst voor Deelnemer:</th>
			<td><?php echo $deelnemer_combobox; ?></td>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Bezoeker:</th>
			<td><input type="text" name="texts_bezoeker" id="texts_bezoeker" value="" class="inp-form"/></td>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Plaats:</th>

			<td><input type="text" name="texts_plaats"	id="texts_plaats" value="" class="inp-form" /></td>
			<td>
			</td>
		</tr>
	<tr>
		<th valign="top">Tekst:</th>
		<td><textarea name="quote_text" id="qoute_text" class="form-textarea" maxlength="400" rows="4" cols="64"></textarea></td>
		<td></td>
	</tr>
	
	<tr>
	<th>Moderated:</th>
	<td><input type="checkbox" name="texts_moderated" id="texts_moderated"	style="margin: 10px" /></td>


	</tr>
	<tr>
	<th>Actief:</th>
	<td><input type="checkbox" name="texts_actief" id="texts_actief" style="margin: 10px" /></td>

	</tr>
	
	<tr>
		<th>&nbsp;</th>
		<td valign="top">
			<div><input type="submit" name="savetextedit" value="Save"></div>
			<!-- <div><a href="/index.php/texts/index/<?php //echo $page_number; ?>">
			<input type="button" name="text_back" value="Terug" /></a></div>-->
			<div><button style='color:black;' onClick="location.href='/index.php/texts/index/<?php echo $page_number; ?>'">Terug</button></div>			
		</td>
		<td></td>
	</tr>	

	</table>
</form>
<?php $this->load->view('footer');?>