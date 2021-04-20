/* $(function() {

    $("#register_email").on('change', function() {
        var email = $(this).val();
        $.post("ajax_functions.php", { email: email }, function(data) {
            $(".db-feedback").html(data);
        });
    });

}); */

function uploadPrescription() {

    hideAlerts();
    let pDescription = document.getElementById("pDescription").value;

    if (pDescription == "") {
        alert("Please Enter a Description !");
        document.getElementById("pDescription").focus();
        return;
    }

    var fd = new FormData();
    var files = $('#pFile')[0].files;

    // Check file selected or not
    if (files.length > 0) {
        fd.append('file', files[0]);
        fd.append('pDescription', pDescription);

        disableBtnWithMessage("pBtn", "Please Wait..");

        $.ajax({
            url: 'functions/prescription.php',
            type: 'post',
            data: fd,
            mimeType: 'multipart/form-data',
            contentType: false,
            processData: false,
            success: function(response) {
                enableBtnWithMessage("pBtn", "Upload");
                $('#pModal').modal('hide');
                document.getElementById("prescriptionTable").innerHTML = response;
                document.getElementById("pDescription").value = "";
                document.getElementById("pFile").value = "";
                $("#successAlertMsg").text("Prescription Saved Successfully !");
                $("#successAlert").css("display", "block");
            },
        });
    } else {
        alert("Please select a file.");
    }
}

function saveBS() {

    hideAlerts();
    let bsValue = document.getElementById("bs").value;

    if (!checkNumber(parseFloat(bsValue)) || bsValue == "") {
        alert("Please Enter a Valid Blood Sugar Value !");
        document.getElementById("bs").focus();
        return;
    }

    if (parseFloat(bsValue) <= 0 || parseFloat(bsValue) > 10) {
        alert("Please Enter Blood Sugar Value Between 0 and 10 !");
        document.getElementById("bs").focus();
        return;
    }

    disableBtnWithMessage("bsbtn", "Please Wait..");
    // jQuery Ajax Post Request
    $.post('functions/blood_sugar.php', {
        bsValue: bsValue
    }, (response) => {
        // response from PHP back-end
        enableBtnWithMessage("bsbtn", "Save BS Value");
        $("#successAlertMsg").text("Blood Sugar Value Saved Successfully !");
        $("#successAlert").css("display", "block");
        document.getElementById("bsTable").innerHTML = response;
        document.getElementById("bs").value = "";
        document.getElementById("bs").focus();
    });
}

function checkNumber(x) {
    // check if the passed value is a number
    if (typeof x == 'number' && !isNaN(x)) {
        // check if it is integer
        if (Number.isInteger(x)) {
            //console.log(`${x} is integer.`);
            return true;
        } else {
            //console.log(`${x} is float.`);
            return true;
        }
    } else {
        //console.log(`${x} is not a number.`);
        return false;
    }
}

$(document).ready(function() {

    $.get("functions/blood_sugar.php?getBsValue=1", function(data, status) {
        document.getElementById("bsTable").innerHTML = data;
    });

    $.get("functions/prescription.php?getPrescriptions=1", function(data, status) {
        document.getElementById("prescriptionTable").innerHTML = data;
    });

    hideAlerts();

});

function hideAlerts() {
    $("#successAlert").css("display", "none");
    $("#dangerAlert").css("display", "none");
}

function disableBtnWithMessage(btn, msg) {
    let x = document.getElementById(btn);
    x.value = msg;
    x.disabled = true;
}

function enableBtnWithMessage(btn, msg) {
    let x = document.getElementById(btn);
    x.value = msg;
    x.disabled = false;
}

function filterByFileName(col = 1) {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("file_name");
    filter = input.value.toUpperCase();
    table = document.getElementById("prescriptionTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[col];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function filterByEmail(field, table, col = 1) {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById(field);
    filter = input.value.toUpperCase();
    table = document.getElementById(table);
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[col];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}