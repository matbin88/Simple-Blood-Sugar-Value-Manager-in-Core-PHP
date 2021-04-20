<?php
include('inc/header.php');
login_check_admin();
include('inc/nav.php');
?>

<div class="row">
    <?php if(isset($_SESSION["successMsg"]) || isset($_SESSION["errorMsg"])) { ?>
    <div class="alert alert-<?php isset($_SESSION["successMsg"])?"success":"danger" ?>  alert-dismissible" role="alert"
        style="text-align:center;">
        <a href="javascript:void(0);" class="close" onclick="hideAlerts();" aria-label="close">&times;</a>
        <?php echo isset($_SESSION["successMsg"])?$_SESSION["successMsg"]:$_SESSION["errorMsg"] ?>
    </div>
    <?php
        unset($_SESSION['successMsg']);
        unset($_SESSION['errorMsg']);
    } 
    ?>
    <div class="col-md-12 mb-4">
        <h4 class="col-md-8" style="padding-left:0px;">Manage Admins</h4>
        <div class="col-md-2"><input type="button" class="btn btn-default form-control" value="Configure Settings">
        </div>
        <div class="col-md-2"><a class="btn btn-default form-control" href="admin.php">Dashboard</a></div>
    </div>
    <div class="col-md-12">
        <hr style="margin-top:0px;margin-bottom:25px;">
    </div>
    <div class="col-md-12 mt-3 mb-4" style="height:auto;overflow:auto;">
        <form action="functions/users.php" method="post" name="adminManipulation">
            <table class="table table-bordered" id="usersTable">

            </table>
        </form>
    </div>


    <p class="col-md-12 mt-4 mb-4">&nbsp;</p>
</div>

<?php
include('inc/footer.php');
?>

<script>
$(document).ready(function() {

    $.get("functions/users.php?getUsers=2", function(data, status) {
        document.getElementById("usersTable").innerHTML = data;
    });

});
</script>