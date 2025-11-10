		<div class="nav">
		
		<div class="table">
		
		<ul class="select">
		
		<li <?php if($this->uri->segment(1)=='users')
		{
			echo ' class="current"';
		} 
		?>><a href="/index.php/users/index"><b>Home</b></a></li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<?php if ($user_role_pdf === TRUE) { ?>
		<ul class="select">
		<li <?php if($this->uri->segment(1)=='texts' && $this->uri->segment(2)=='index')
		{
			echo ' class="current"';
		} 
		?>><a href="/index.php/pdf/index"><b>Facturen</b></a></li>

		</ul>
		<div class="nav-divider">&nbsp;</div>
		<?php } ?>	

		 
		<ul class="select">		
		<li><a href="/index.php/users/logout"><b>Logout</b></a></li>
		</ul>

		<div class="clear"></div>
		</div>
		<div class="clear"></div>
		</div>
		