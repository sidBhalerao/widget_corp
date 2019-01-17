<?php  require_once("../includes/session.php") ?>
<?php  require_once("../includes/db_connection.php") ?>
<?php  require_once("../includes/functions.php") ?>

<?php $layout_contex = "public"; ?>
<?php  include("../includes/layouts/header.php") ?>
<?php find_selected_page(true); ?>
	
<div id="main">
	<div id="navigation">
		<?php echo public_naviation($current_subject, $current_page); ?>
	</div>

	<div id="page">
		<?php if($current_page){ ?>

			<h2><?php echo htmlentities($current_page["menu_name"]); ?><br /></h2>
			<?php echo nl2br(htmlentities($current_page["content"])); ?><br />  
		
		<?php } else{ ?>
			<br />
			
			<p><b>Welcome !</b></p>

		<?php  }  ?>		
	</div>
</div>                                                                                                                    
<?php 
	include("../includes/layouts/footer.php") 
?>