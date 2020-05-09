<?php
require('sql.php');
if (!session_id()) {
    session_start();
  }
if (isset($_POST['login'])) {
    if (ctype_alnum($_POST['username']) and isset($_POST['pwd'])) {
        if(userAuth('login', $_POST['username'], $_POST['pwd'])) {
            die(header('location: admin.php'));
        }
    }
}

if (isset($_GET['logout'])) {
    userAuth('logout', 'admin', 'admin');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo siteName; ?></title>
  <meta name="description" content="<?php echo siteDescription; ?>">
  <meta name="author" content="Reinier Nel https://www.reinier.co.za">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
</head>
    <body>
        <div class="container">
            <div class="row">
                <div style="height:100px;"></div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                </div>

                <div class="col-sm-4">
                <h1>Login</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" id="username">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input type="password" name="pwd" class="form-control" placeholder="Enter password" id="pwd">
                    </div>
                    <button type="submit" name="login" value="true" class="btn btn-primary">Login</button>
                    <a href="./" class="btn btn-info">Back</a>
                </form>
                </div>

                <div class="col-sm-4">
                </div>
            </div>
        </div>
        <?php
            //Close DB connection see closeDB function in sql.php
            closeDB();
            //Show set vars usefull for debugging  use the following sql to enable debugging:
            //UPDATE `settings` SET `value` = '0' WHERE `settings`.`name` = 'debug';
            if (debug == 1) {
                $debugLog['debugStatus'] = "on";
                echo '<pre>';
                echo var_dump(get_defined_vars());
                echo '</pre>';
            }
        ?>
    </body>
</html>
