



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
	     	<h3 class="line"><span>Benvenuto</span></h3>
	    	 <?php echo $row ['page_text']; ?>
	    	</div>
	    	<div id="botcon"></div>
	    	</div>
		</div>
		
	      <div id="clear"></div>
	      
		  <?php include ('view/footer.php'); ?>


	
	</body>


	</html>