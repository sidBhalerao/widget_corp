
		<div id="footer">Copyright <?php echo date("Y")?> , Widget Corp</div>
	</body>
</html>

<?php
// Close the conncetion.
if(isset($connection)){
	mysqli_close($connection);
}
?>
