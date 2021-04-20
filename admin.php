<?php
include('inc/header.php');
login_check_admin();
include('inc/nav.php');
?>

<div class="row">

    <div class="col-md-12 mb-4">
        <h4 class="col-md-8" style="padding-left:0px;">Dashboard</h4>
        <div class="col-md-2"><input type="button" class="btn btn-default form-control" value="Configure Settings"></div>
        <div class="col-md-2"><a class="btn btn-default form-control" href="manage_admins.php">Create More Admins</a></div>
	</div>
	<div class="col-md-12"><hr style="margin-top:0px;margin-bottom:25px;"></div>
    <div class="col-md-3">
        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Filter By Email">
    </div>
    <div class="col-md-3">
        <input type="button" name="userFilterBtn" id="userFilterBtn" value="Filter" class="btn btn-primary form-control"
            onclick="filterByEmail('user_name','usersTable');">
    </div>
    <div class="col-md-12 mt-3 mb-4" style="height:auto;overflow:auto;">
        <table class="table table-bordered" id="usersTable">

        </table>
    </div>
    
    <div class="col-md-3">
        <input type="text" name="bs_name" id="bs_name" class="form-control" placeholder="Filter By Email">
    </div>
    <div class="col-md-3">
        <input type="button" name="bsFilterBtn" id="bsFilterBtn" value="Filter" class="btn btn-primary form-control"
            onclick="filterByEmail('bs_name','bsTable');">
    </div>
    <div class="col-md-12 mt-3 mb-4" style="height:auto;overflow:auto;">
        <table class="table table-bordered" id="bsTable">

        </table>
    </div>

    <div class="col-md-3">
        <input type="text" name="file_name" id="file_name" class="form-control" placeholder="Filter By File Name">
    </div>
    <div class="col-md-3">
        <input type="button" name="fileFilterBtn" id="fileFilterBtn" value="Filter" class="btn btn-primary form-control"
            onclick="filterByFileName(2);">
    </div>
    <div class="col-md-12 mt-3" style="height:auto;overflow:auto;">
        <table class="table table-bordered" id="prescriptionTable">

        </table>
    </div>
    <p class="col-md-12 mt-4 mb-4">&nbsp;</p>
</div>

<?php
include('inc/footer.php');
?>

<script>
$(document).ready(function() {

    $.get("functions/users.php?getUsers=1", function(data, status) {
        document.getElementById("usersTable").innerHTML = data;
    });

});
</script>