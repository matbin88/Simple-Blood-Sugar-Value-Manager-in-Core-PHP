<?php
include('inc/header.php');
include('inc/nav.php');
login_check_pages();

$email = "";
$code = "";

?>


<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <?php display_message(); ?>
        <?php activate_user(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-login">
            <div class="panel-heading">
                <div class="row">
                    <div class="col">
                        <h4>Complete Your Registration</h4>
                    </div>
                </div>
                <hr>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="register-form" method="post" role="form">
                            <div class="form-group">
                                <p style="color:red;">* Password Characters must be between 8 and 15</p>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" tabindex="2" class="form-control"
                                    placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="confirm_password" id="confirm-password" tabindex="2"
                                    class="form-control" placeholder="Confirm Password" required>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-3">
                                        <input type="hidden" name="email" value="<?php echo $email!=""?$email:$_GET['email']; ?>">
                                        <input type="hidden" name="code" value="<?php echo $code!=""?$code:$_GET['code']; ?>">
                                        <input type="submit" name="register-submit" id="register-submit" tabindex="4"
                                            class="form-control btn btn-register" value="Complete Registration Now">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php
include('inc/footer.php');
?>