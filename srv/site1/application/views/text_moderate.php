<style>.header {
margin: 20px;
padding: 10px;
height: 100px;
}

.menu {
position: absolute;
left: 15px;
top: 160px;
width: 200px;
}

.content {
top: 0;
margin-left: 200px;
margin-right: 15px;
}
</style>

<?php $this->load->view('header');?>
<?php $this->load->view('menulinks');?>
<div class="content">
<h1><?php echo "Welkom " . $company_name; ?></h1>
<h1><?php echo "Tekst moderatie beheer"; ?></h1>

<?php foreach ($results->result() as $row) {
		echo "<p>[" . $row->content_text  . "]</p>";	
}
?>
<p><?php echo $paginate_links;?></p>
</div> 
<?php //$this->load->view('footer');?>

