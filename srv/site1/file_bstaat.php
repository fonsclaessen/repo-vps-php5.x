<?php
		header('Content-Type: application/json');
		$file = isset($_POST['file']) ? $_POST['file'] : '';
		echo json_encode(array('exists' => file_exists($file)), 'pad' => $file);
?>
