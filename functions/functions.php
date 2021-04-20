<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function clean($str)
{
    return htmlentities($str);
}

function redirect($location)
{
    header("location: {$location}");
    exit();
}

function set_message($message)
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        $message = "";
    }
}

function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function token_generator()
{
    $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    return $token;
}


function email_exists($email)
{
    $email = filter_var($email,FILTER_SANITIZE_EMAIL);
    $query = "SELECT id FROM users WHERE email = '$email'";
    if (row_count(query($query))) {
        return true;
    } else {
        return false;
    }
}

function user_exists($user)
{
    $user = filter_var($user,   FILTER_SANITIZE_STRING);
    $query = "SELECT id FROM users WHERE username = '$user'";
    if (row_count(query($query))) {
        return true;
    } else {
        return false;
    }
}

function get_user_email($user_id)
{
    $query = "SELECT email FROM users WHERE id = '$user_id'";
    $result = query($query);
    if (row_count($result) == 1) {
        while ($row = mysqli_fetch_row($result)) {
            return $row[0];
        }
    }
    return "";
}

function get_current_user_id()
{
    if(isset($_SESSION["email"]))
    {
        $email = filter_var($_SESSION["email"],FILTER_SANITIZE_EMAIL);
        $query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
        $result = query($query);
        if (row_count($result) == 1) {
            while ($row = mysqli_fetch_row($result)) {
                return $row[0];
            }
        }
        return 0;
    }
    return 0;
}

function is_admin()
{
    if (isset($_SESSION['sblms_admin']) || isset($_COOKIE['sblms_admin']))
        return true;
    else
        return false;
}

function is_user()
{
    if (isset($_SESSION['email']) || isset($_COOKIE['email']))
        return true;
    else
        return false;
}

function validate_user_registration()
{
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        if (strlen($username) < 3) {
            $errors[] = "Your Username cannot be less then 3 characters";
        }
        if (strlen($username) > 20) {
            $errors[] = "Your Username cannot be bigger then 20 characters";
        }
        if (email_exists($email)) {
            $errors[] = "Sorry that Email is already is taken";
        }
        if (user_exists($username)) {
            $errors[] = "Sorry that Username is already is taken";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert alert alert-danger">' . $error . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button></div>';
            }
        } else {
            $username   = filter_var($username,     FILTER_SANITIZE_STRING);
            $email      = filter_var($email,        FILTER_SANITIZE_EMAIL);
            $password   = filter_var("temp",     FILTER_SANITIZE_STRING);
            $password   = password_hash("temp",PASSWORD_DEFAULT );
            createuser($username, $email, $password);
        }
    }
}

function createuser($username, $email, $password)
{
    global $url;
    
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);
    $password   = password_hash($password,PASSWORD_DEFAULT );
    $token = md5($username . microtime());
    $sql = "INSERT INTO users(username,email,password,token,activation,admin) ";
    $sql .= "VALUES('$username','$email','$password','$token',0,0)";
    confirm(query($sql));
    //set_message("<h5 align='center'>Please Click <a href='$url/activate.php?email=$email&code=$token'>Here</a> to Activate Your Account </h5>");
    $subject = "Activate Account";
    $msg = "Please Click the link below to Activate Your Account $url/activate.php?email=$email&code=$token";
    $headers = "From: sblms@gmail.com";
    send_email($email, $subject, $msg, $headers);
    redirect('index.php');
}

function send_email($email, $subject, $msg, $headers)
{
    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '';     //SMTP username
        $mail->Password   = '';     //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('sblms@sblms.com', 'Mailer');
        $mail->addAddress($email, '');     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo('info@sblms.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $msg;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        set_message("<h5 align='center'>Please Check Your Mail for Activation Link !</h5>");
    } catch (Exception $e) {
        set_message("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
    //return mail($email, $subject, $msg, $headers);
}

function activate_user()
{
    global $email,$code;
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = clean($_POST['email']);
        $code = clean($_POST['code']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);

        if (strlen($password) < 8 || strlen($password) > 15) {
            $errors[] = "Your Password Length must be between 8 to 15 characters";
        }
        if ($password != $confirm_password) {
            $errors[] = "The password was not confirmed correctly";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert alert alert-danger">' . $error . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button></div>';
            }
        } else {
            
            $email      = filter_var($email,    FILTER_SANITIZE_EMAIL);
            $code       = filter_var($code, FILTER_SANITIZE_STRING);
            $password   = filter_var($password,     FILTER_SANITIZE_STRING);
            $password   = password_hash($password,  PASSWORD_DEFAULT );

            $query = "SELECT id FROM users WHERE email='$email' AND token='$code'";
            
            $queryEmail = "SELECT id FROM users WHERE email='$email'";
            $result = query($query);
            $resultEmail = query($queryEmail);
            confirm($result);
            confirm($resultEmail);

            if (row_count($result) == 1) {
                $query = "UPDATE users SET activation = 1, token = 0, password = '$password' Where email='$email' and token='$code'";

                confirm(query($query));
                set_message("<div class='alert alert-success'>Your Account has been Activated Please Login</div>");
                redirect('login.php');
            } else {
                if (row_count($resultEmail) == 1) {
                    set_message("<div class='alert alert-success'>Your account is already activated</div>");
                    redirect('login.php');
                } else {
                    set_message("<div class='alert alert-danger'>The activation link is incorrect. Please create an account</div>");
                    redirect('register.php');
                }
            }
        }
    }
}

function validate_user_login()
{
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $remember = "";
        
        if (empty($email)) {
            $errors[] = "Email field cannot be empty";
        }
        if (empty($password)) {
            $errors[] = "Password field cannot be empty";
        }
        if (empty($errors)) {
            if (user_login($email, $password, $remember)) {
                if(isset($_SESSION["sblms_admin"]))
                    redirect('admin.php');
                else
                    redirect('user.php');
            } else {
                $errors[] = "your email or password is incorrect. please try again";
            }
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert alert alert-danger">' . $error . '
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button></div>';
            }
        }
    }

}

function user_login($email, $password, $remember="")
{
    $password   = filter_var($password, FILTER_SANITIZE_STRING);
    $email      = filter_var($email,    FILTER_SANITIZE_EMAIL);

    $query = "SELECT id,username,password,admin FROM users WHERE email='$email' LIMIT 1";
    //echo $query;

    $result = query($query);
    if (row_count($result) == 1) {
        while ($row = mysqli_fetch_row($result)) {
            if(password_verify($password, $row[2]))
            {
                /* if ($remember == "1") {
                    setcookie('email', $email, time() + (86400 * 30));
                } */
                $_SESSION['username'] = $row[1];
                $_SESSION['email'] = $email;
                //set session if admin
                if($row[3] == 1)
                {
                    $_SESSION['sblms_admin'] = $email;
                }
                return true;
            } else {
                return false;
            }
        }
        return false;
    } else {
        return false;
    }
}

function login_check_admin()
{
    if (isset($_SESSION['sblms_admin']) || isset($_COOKIE['sblms_admin'])) {
        return true;
    } else {
        redirect('index.php');
    }
}

function login_check_user()
{
    if (isset($_SESSION['sblms_admin']) || isset($_COOKIE['sblms_admin'])) {
        redirect('admin.php');
    } elseif (isset($_SESSION['email']) || isset($_COOKIE['email'])) {
        return true;
    } else {
        redirect('index.php');
    }
}

function login_check_pages()
{
    if (isset($_SESSION['sblms_admin']) || isset($_COOKIE['sblms_admin'])) {
        redirect('admin.php');
    } elseif (isset($_SESSION['email']) || isset($_COOKIE['email'])) {
        redirect('user.php');
    } else {
        redirect('index.php');
    }
}

function recover()
{
    global $url;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['cancel-submit'])) {
            redirect('login.php');
        }
        if (isset($_POST['recover-submit'])) {
            $email = $_POST['email'];
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $query = "SELECT id FROM users WHERE email='$email'";
            $result = query($query);
            if (row_count($result) == 1) {
                $token = token_generator();
                $query = "UPDATE users set token='$token' WHERE email='$email'";
                query($query);
                set_message('Please Check Your Email or Spam Folder For Recover Link');
                $subject = "Activate Account";
                $msg = "Please Click the link below to Activate Your Account
                $url/code.php?email=$email&code=$token";
                $headers = "From: x24web@gmail.com";
                send_email($email, $subject, $msg, $headers);
                redirect('index.php');
            } else {
                set_message("This Email does not Exist");
                redirect('recover.php');
            }
        }
        echo "<p class='alert alert-danger text-center'>";
        display_message();
        echo "</p>";
    }
}

function check_code()
{
    if ($_SERVER['REQUEST_METHOD'] == "GET")
    {
        $email = $_GET['email'];
        $token = $_GET['token'];
        $email  = filter_var($email,   FILTER_SANITIZE_EMAIL);
        $token  = filter_var($token,    FILTER_SANITIZE_STRING);
        $query = "SELECT id FROM users WHERE email='$email' AND token='$token'";
        $result = query($query);
        if (row_count($result) == 1) {
            return true;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['reset-password-submit'])){
            $email = $_GET['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            $email              = filter_var($email,               FILTER_SANITIZE_EMAIL);
            $password           = filter_var($password,            FILTER_SANITIZE_STRING);
            $confirm_password   = filter_var($confirm_password,    FILTER_SANITIZE_STRING);

            if($password == $confirm_password){
                $password   = password_hash($password,PASSWORD_DEFAULT );
                $query = "UPDATE users set password='$password', token='0' WHERE email='$email'";
                query($query);
                set_message('<p class="alert alert-success">The password has been updated. Can Be Login Now</p>');
                redirect('login.php');
            }
        }
    }
}