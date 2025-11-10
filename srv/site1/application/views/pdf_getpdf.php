<?php
$this->load->view ( 'header' );
?>
<div class="content">
<h1><?php
echo "Welkom " . $company_name;
?></h1>
<h1>Factuur Beheer</h1>

<p><?php echo $pdf_id; ?></p>
<p><?php echo $deelnemer; ?></p>
<p><?php echo $werknemer_id; ?></p>
<p><?php echo $user_role_pdf; ?></p>
<p><?php echo $pdf_temp; ?></p>

</div>


<div>&nbsp;dddd</div>

<?php
$this->load->view ( 'footer' );
?>
