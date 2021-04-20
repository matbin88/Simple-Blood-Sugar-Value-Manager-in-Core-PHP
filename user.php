<?php
include('inc/header.php');
login_check_user();
include('inc/nav.php');
?>

<div class="row">

    <div class="alert alert-success alert-dismissible" role="alert" id="successAlert">
        <a href="javascript:void(0);" class="close" onclick="hideAlerts();" aria-label="close">&times;</a>
        <p id="successAlertMsg" style="margin:0px;padding:0px;text-align:center;"></p>
    </div>
    <div class="alert alert-danger alert-dismissible" role="alert" id="dangerAlert">
        <a href="javascript:void(0);" class="close" onclick="hideAlerts();" aria-label="close">&times;</a>
        <p id="dangerAlertMsg" style="margin:0px;padding:0px;text-align:center;"></p>
    </div>

    <div class="col-md-12 mb-4">
        <h4>My Space</h4>
        <hr>
    </div>
    <div class="col-md-8">
        <input type="text" name="bs" id="bs" class="form-control" placeholder="Enter Blood Sugar value">
    </div>
    <div class="col-md-4">
        <input type="button" name="bsbtn" id="bsbtn" value="Save BS Value" class="btn btn-success form-control"
            onclick="saveBS();">
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
            onclick="filterByFileName();">
    </div>
    <div class="col-md-3">
    </div>
    <div class="col-md-3">
        <button type="button" name="addPrescriptionBtn" id="addPrescriptionBtn" value="Add Prescription"
            class="btn btn-primary form-control" data-toggle="modal" data-target="#pModal">
            Add Prescription
        </button>
    </div>
    <div class="col-md-12 mt-3" style="height:auto;overflow:auto;">
        <table class="table table-bordered" id="prescriptionTable">

        </table>
    </div>
    <p class="col-md-12 mt-4 mb-4">&nbsp;</p>
</div>

<div class="modal fade" id="pModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Prescription</h4>
            </div>
            <div class="modal-body">
                <label for="pFile">File</label>
                <input type="file" name="pFile" id="pFile" class="form-control">
                <label for="pDescription">Description</label>
                <textarea name="pDescription" id="pDescription" class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="uploadPrescription();" id="pBtn">Upload</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
include('inc/footer.php');
?>