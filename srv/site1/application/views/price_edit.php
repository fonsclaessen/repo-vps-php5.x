<?php
$this->load->view ( 'header' );
?>

<div class="content">
<h1><?php
echo $deelnemer;
?></h1>

<h3>Image uploaded:&nbsp;&nbsp;&nbsp;</h3>
<div><img
	src="/image_price/<?php
	echo $deelnemer . "/" . $edit_image;
	?>"
	width=100 height=60 /></div>
</br>
<?php
$this->load->view ( 'upload_form', array ('error' => $error ) );
?>
<input type="hidden" name="edit_id_hidden"
	value="<?php
	echo $edit_id;
	?>"> <input type="hidden" name="edit_page_hidden"
	value="<?php
	echo $page_number;
	?>">
</form>
<form id="prices_edit" action="/index.php/prices/save" method="post"><input
	type="hidden" name="edit_id_hidden" value="<?php
	echo $edit_id;
	?>"> <input type="hidden" name="edit_page_hidden"
	value="<?php
	echo $page_number;
	?>">
<div>&nbsp;</div>
<div>Omschrijving:</div>
<div><textarea id="price_omschrijving" class="imput small"
	maxlength="400" rows="4" cols="40" name="price_omschrijving"><?php
	echo $edit_omschrijving;
	?></textarea></div>
<div>&nbsp;</div>
<div>Tekst:</div>
<div><textarea align="left" id="price_text" class="imput small"
	maxlength="400" rows="4" cols="40" name="price_text"><?php
	echo $edit_tekst;
	?></textarea></div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>Prijs teller:&nbsp;&nbsp;&nbsp;<input name="edit_trigger_counter"
	id="edit_trigger_counter"
	value="<?php
	echo $edit_trigger_counter;
	?>" size="3" type="text"></div>
<div>&nbsp;</div>
<div>Actief<input type="checkbox" name="price_actief" id="price_actief"
	style="margin: 10px"
	<?php
	echo ($edit_actief == "A" ? 'checked="checked"' : '');
	?> /></div>
<!-- <input readonly name="edit_actief" id="edit_actief" value='<?php //echo ($edit_actief=="A" ? "Actief" :  "NIET Actief");?>' size="15" type="text">-->

<div>&nbsp;</div>
<div>Aangemaakt op:&nbsp;&nbsp;&nbsp;<?php
echo $edit_created_at;
?></div>
<div>&nbsp;</div>
<div><input type="submit" name="tedit<?php
echo $edit_id;
?>"
	id="tekst<?php
	echo $edit_id;
	?>" value="Save" /></div>

<!-- <div><a href="/index.php/prices/index"><input type="button"
	name="terug<?php
	//	echo $edit_id;
	?>"
	id="terug<?php
	//echo $edit_id;
	?>" value="Terug" /> </a></div>-->
<div>
<button style='color: black;'
	onClick="location.href='/index.php/prices/index'">Terug</button>
</div>
</form>
</div>
<p>&nbsp;</p>
<?php
$this->load->view ( 'footer' );
?>

