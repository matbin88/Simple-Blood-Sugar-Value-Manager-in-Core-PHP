<?php
include('inc/header.php');
include('inc/nav.php');
?>
<div class="jumbotron">
    <h1 class="text-center">
		<?php 
		if(isset($_SESSION['message'])){display_message();}else{echo "Web App to Manage Blood Sugar Vallues and Prescriptions.";} ?>
    </h1>
</div>
<?php
include('inc/footer.php');
?>