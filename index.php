<?php
// DO NOT REMOVE
require('sql.php');
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
    <!-- BANNER START -->
    <div class="row">
      <div class="col-sm-12">
        <div class="jumbotron">
          <h1><?php echo siteName; ?></h1>
          <p><?php echo siteDescription; ?></p>
        </div>
      </div>
    </div>
    <!-- BANNER END -->
    <!--SEARCH START -->
    <div class="row sticky-top bg-light">
      <div class="col-sm-12">
        <input class="form-control" id="search" type="text" placeholder="Search..">
        <div style="padding-bottom: 30px;">
        </div>
      </div>
    </div>
    <!-- SEARCH END -->
    <!-- CONTENT START -->
    <div class="row">
        <div class="col-sm-12">
          <div class="card-columns" id="showTests">
          <?php
          //Loop though test details see getTest() function in sql.php for more details
          foreach (getTest() as $testDetails) {
            //Print out the card with some details
            echo '<div class="card" id="test">
              <div class="card-body">
                <h4 class="card-title">' . $testDetails['name'] . '</h4>
                <p class="card-text">' . $testDetails['description'] . '</p>
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#test' . $testDetails['id'] . '">Take Test</a>
              </div>
            </div>';
            //Print out the Modal with full details
            echo '<div class="modal" id="test' . $testDetails['id'] . '">
              <div class="modal-dialog">
                <div class="modal-content">
                  <!-- Modal Header -->
                  <form action="test.php" method="post">
                  <div class="modal-header">
                    <h4 class="modal-title">' . $testDetails['name'] . '</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <!-- Modal body -->
                  <div class="modal-body">
                    <p class="card-text">' . $testDetails['description'] . '</p>
                    <p><i class="fas fa-clock"></i> ' . $testDetails['time'] . ' Min</p>
                    <p><i class="fas fa-tasks"></i> ' . countQuestions($testDetails['id']) . ' Question</p>
                    <p><i class="fas fa-check-circle"></i> ' . passMark . '% Score required to pass</p>
                    <p class="text-danger">' . testInstruction . '</p>
                    <div class="form-group">
                      <label for="name">Your Name:</label>
                      <input type="text" name="name" class="form-control" placeholder="Enter your Name" pattern="["/^[a-zA-Z]+$/]" id="name">
                    </div>
                    <div class="form-group">
                      <label for="email">Email address:</label>
                      <input type="email" name="email" class="form-control" placeholder="Enter email" id="email">
                    </div>
                  </div>
                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="submit" name="test" value="' . $testDetails['id'] . '" class="btn btn-success">Start Test</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>';
          }
          ?>
          </div>
          <div style="padding-bottom: 30px;">
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
  </div>
  <!-- Search bar JS -->
  <script type="text/javascript">
    $(document).ready(function(){
    $("#search").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#showTests #test").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
    });
  </script>
</body>
</html>
