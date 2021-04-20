<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php if(is_admin()){echo "admin.php";}elseif(is_user()){echo "user.php";}else{echo "index.php";} ?>">SBLMS</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse pull-right">
            <ul class="nav navbar-nav navbar-right">
                <?php if(!isset($_SESSION['email'])): ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="Register.php">Register</a></li>
                <?php else: ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">
                        <?php echo $_SESSION['username']; ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php if(is_admin()){echo "admin.php";}else{echo "user.php";} ?>">Dashboard</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php endif;?>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>