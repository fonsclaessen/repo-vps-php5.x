<?php
$this->load->view ( 'header' );
?>

<div class="content">
<h1>Deelnemer:&nbsp;&nbsp;&nbsp;<?php
echo $deelnemer;
?></h1>
<br />

<form id="texts_edit"
	action="/index.php/texts/save/<?php
	echo $moderate_save;
	?>"
	method="post"><input type="hidden" name="text_id_hidden"
	value="<?php
	echo $sqlrow->id;
	?>"> <input type="hidden" name="text_page_hidden"
	value="<?php
	echo $page_number;
	?>">

<table border="0" cellpadding="0" cellspacing="0" id="id-form">
	<tr>
		<th valign="top">Text nummer:</th>
		<td><?php
		echo $sqlrow->id;
		?></td>
		<td></td>
	</tr>
	<tr>
		<th valign="top">Deelnemer:</th>
		<td><input type="text=" name="texts_deelnemer" id="texts_deelnemer"
			value="<?php
			echo $sqlrow->deelnemer;
			?>" class="inp-form" /></td>
		<td></td>
	</tr>
	<tr>
		<th valign="top">Bezoeker:</th>
		<td><input type="text=" name="texts_bezoeker" id="texts_bezoeker"
			value="<?php
			echo $sqlrow->naam_bezoeker;
			?>" class="inp-form" /></td>
		<td></td>
	</tr>
	<tr>
		<th valign="top">Plaats:</th>

		<td><input type="text=" name="texts_plaats" id="texts_plaats"
			value="<?php
			echo $sqlrow->woonplaats_bezoeker;
			?>"
			class="inp-form" /></td>
		<td></td>
	</tr>


	<tr>
		<th valign="top">Tekst:</th>
		<td><textarea name="quote_text" id="qoute_text" maxlength="400"
			rows="4" cols="64" class="form-textarea"><?php
			echo $sqlrow->content_text;
			?></textarea></td>
		<td></td>
	</tr>

	<tr>
		<th>Moderated:</th>
		<td><input type="hidden" name="texts_moderated" value="off" /> <input
			type="hidden" name="texts_actief" value="off" /><input
			type="checkbox" name="texts_moderated" id="texts_moderated"
			style="margin: 10px"
			<?php
			echo ($sqlrow->moderated == "Y" ? 'checked="checked"' : '');
			?> /></td>


	</tr>
	<tr>
		<th>Actief:</th>
		<td><input type="checkbox" name="texts_actief" id="texts_actief"
			style="margin: 10px"
			<?php
			echo ($sqlrow->actief == "A" ? 'checked="checked"' : '');
			?> /></td>

	</tr>


	<tr>
		<th valign="top">Aantal keren getoond:</th>
		<td><?php
		echo $sqlrow->display_count;
		?></td>
		<td></td>
	</tr>

	<tr>
		<th valign="top">Laatst gewijzigd op:</th>
		<td><?php
		echo $sqlrow->created_at;
		?></td>
		<td></td>
	</tr>

	<tr>
		<th>&nbsp;</th>
		<td valign="top">
		<div><input type="submit" name="savetextedit" value="Save"></div>
<!-- 	<p><a
			href="/index.php/texts/<?php
	//		echo ($moderated === FALSE ? 'index/' : 'moderate/')?><?php
	//		echo $page_number;
			?>"><input type="button" name="text_back" value="Terug" /></a>  -->
			<div><button style='color:black;' onClick="location.href='/index.php/texts/<?php echo ($moderated === FALSE ? 'index/' : 'moderate/')?><?php echo $page_number; ?>'">Terug</button></div>
		</td>
		<td></td>
	</tr>

</table>

</form>
</div>
<?php
$this->load->view ( 'footer' );
?>