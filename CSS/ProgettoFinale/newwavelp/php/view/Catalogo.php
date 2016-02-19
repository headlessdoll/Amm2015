<?php

   include ('view/db.php');
   
   $query = mysql_query ("SELECT page_title,page_text,meta_key,meta_des FROM `page_text` WHERE page_name = 'nuovi'", $db);

    $row = mysql_fetch_array($query);
	
	
?>



<!DOCTYPE html>

	<html>
		  <head>
				  <title><?php echo $row ['page_title']; ?></title>
					<link href="css/style.css" rel="stylesheet" type="text/css" />
		  </head>
	  <body>
				    <?php include ('view/head.php'); ?>
	     <div id="conteiner">
					
					<?php include ('view/leftbar.php'); ?>

		     <div id="content">
		     <div id="topcon"></div>
		     <div id="backcon">
			 
	    	 <?php echo $row ['page_text']; ?>
			 
			 
	    	</div>
	    	<div id="botcon"></div>
	    	</div>
		</div>
		
	      <div id="clear"></div>
	     
		 <?php include ('view/footer.php'); ?>


	
	</body>


	</html>