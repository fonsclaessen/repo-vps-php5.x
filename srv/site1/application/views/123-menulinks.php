<div class="nav">

   <div class="table">

      <?php if ($_SESSION ['deelnemer'] != 'opd') { ?>
         <ul class="select">

            <li <?php
            if ($this->uri->segment(1) == 'users') {
               echo ' class="current"';
            }
            ?>><a href="/index.php/users/index"><b>
<?php if ($_SESSION ["role"] != 'administrator')    { ?>
			
			Diagrammen
<?php } else { ?>			
			Lijst
<?php } ?>						
			</b></a></li>
         </ul>

         <div class="nav-divider">&nbsp;</div>

	<?php if 
		(
		(($_SESSION ["role"] == 'administrator') && ((isset($_SESSION ['adminusergrid'])) && ($_SESSION ['adminusergrid'] == true) )    ) 
			||
		($_SESSION ["role"] != 'administrator')    
		) 			
		{ ?>			
         <?php if ($user_role_pdf === TRUE) { ?>
            <ul class="select">
               <li <?php
               if ($this->uri->segment(1) == 'pdf' && $this->uri->segment(2) == 'index_grid') {
                  echo ' class="current"';
               }
               ?>>

			<a href="/index.php/pdf/index_grid"><b>Facturen</b></a></li>



            </ul>
            <div class="nav-divider">&nbsp;</div>
         <?php } ?>	
		     <?php } ?>	
      <?php } ?>	

	<?php if (($_SESSION ["role"] == 'administrator') && ((isset($_SESSION ['adminusergrid'])) && ($_SESSION ['adminusergrid'] == true) )    ) 
	{ ?>			

	<ul class="select">		
	<li><a href="/index.php/users/indexcharts"><b>Diagrammen</b></a></li>
	</ul>
<?php }?>      
      <ul class="select">		
         <li><a href="/index.php/users/firstlogin"><b>Nieuw wachtwoord invoeren</b></a></li>
      </ul>

      <div class="nav-divider">&nbsp;</div>

      <ul class="select">		
         <li><a href="/index.php/users/logout"><b>Afmelden</b></a></li>
      </ul>

<?php  if (isset($_SESSION['komtvanuren'])) { ?>
	<ul class="select">		
	<li><a href="/index.php/users/terugnaarkeuzemenu"><b>Terug</b></a></li>
	</ul>
<?php } ?>

      
      <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
