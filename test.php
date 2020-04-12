<?php
require('sql.php');
//Handle POST['test']
if (isset($_POST['test'])) {
  if (!empty($_POST['test'])) {
    if (is_int((int)$_POST['test'])) {
      $tid = (int)$_POST['test'];
      $testData = compileTest((int)$_POST['test']);
      $timeMin = $testData[$_POST['test']]['time'];
      $timeSec = (int)$timeMin * 60;
      $testName = $testData[$_POST['test']]['name'];
      $testDescription = $testData[$_POST['test']]['description'];
    }
  }
}
//Handle POST['name']
if (isset($_POST['name'])) {
  if (!empty($_POST['name'])) {
    if (preg_match("/^[a-zA-Z-\040]+$/", $_POST['name'])) {
      $participantName = $_POST['name'];
    } else {
      $participantName = "Unkown";
    }
  }
}
//Handle POST['email']
if (isset($_POST['email'])) {
  if (!empty($_POST['email'])) {
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $participantEmail = $_POST['email'];
    }
  }
}
//Redirect back to main page if input is invalid
if (isset($testData) and isset($participantName) and isset($participantEmail)) {
  $checkParticipantDetails = getParticipantsFromTest((int)$tid, $participantEmail);
  //Check that rewrite is set to 1 check table settings
  if (rewrite == 1) {
    newParticipants((int)$tid, $participantName, $participantEmail);
    $getParticipantDetails = getParticipantsFromTest((int)$tid, $participantEmail);
    $pid = $getParticipantDetails['id'];
    $startTime = $getParticipantDetails['date'];
  } else {
    if (is_array($checkParticipantDetails)) {
      die(header('Location: done.php?rewrite'));
    } elseif ($checkParticipantDetails == true) {
      newParticipants((int)$tid, $participantName, $participantEmail);
      $getParticipantDetails = getParticipantsFromTest((int)$tid, $participantEmail);
      $pid = $getParticipantDetails['id'];
      $startTime = $getParticipantDetails['date'];
    } else {
      die(header('Location: done.php?rewrite'));
    }
  }
} else {
  //die('issues');
  //die(header('Location: ./'));
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
    <!-- BANNER START -->
    <div class="row">
      <div class="col-sm-12">
        <div class="jumbotron">
          <h1><?php echo $testName; ?></h1>
          <p><?php echo $testDescription; ?></p>
        </div>
      </div>
    </div>
    <!-- BANNER END -->
    <!-- CONTENT START -->
    <div class="row sticky-top bg-light">
      <!-- Timer -->
      <div class="col-sm-12">
        <h3>Time Left on test</h3>
        <div class="progress">
          <div class="progress-bar" id="countdown"></div>
        </div>
        <!-- spacer -->
        <div style="padding-bottom: 30px;">
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-12">
        <form action="done.php" method="post">
        <?php
        //Compile the quistions and awnserts in the test and desply them as a heading and radio buttons
        //See
        foreach ($testData[(int)$_POST['test']] as $qid => $questions) {
          if (is_array($questions)) {
            echo '<h3>' . $questions['question'] . '</h3>';
            foreach ($questions['answers'] as $aid => $answers) {
            echo '<div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="question[' . $qid . ']" value="' . $aid . '">' . $answers . '
              </label>
            </div>';
            }
            echo '<hr>';
          }
        }
        ?>
        <input type="hidden" name="testID" value="<?php if (isset($tid)) { echo $tid; } ?>">
        <input type="hidden" name="testName" value="<?php if (isset($testName)) { echo $testName; } ?>">
        <input type="hidden" name="participantID" value="<?php if (isset($pid)) { echo $pid; } ?>">
        <input type="hidden" name="startTime" value="<?php if (isset($startTime)) { echo $startTime; } ?>">
        <input type="hidden" name="name" value="<?php if (isset($participantName)) { echo $participantName; } ?>">
        <input type="hidden" name="email" value="<?php if (isset($participantEmail)) { echo $participantEmail; } ?>">
        <input type="hidden" id="cheated" name="cheated" value="0">
        <button type="submit" id="submitTest" class="btn btn-primary">Submit</button>
        </form>
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
      echo '<pre>';
      echo var_dump(get_defined_vars());
      echo '</pre>';
    }
    ?>
  </div>
</body>
<?php
// Below JS is used to update the timer on screen and submit the form when it hits 0
?>
<script type="text/javascript">
  var seconds = <?php echo $timeSec; ?>;
  function countdown() {
      seconds = seconds - 1;
      percentage = seconds / <?php echo $timeSec; ?> * 100;
      if (seconds < 0) {
          document.getElementById('submitTest').click();
      } else {
          document.getElementById("countdown").style.width = percentage.toFixed(0)+"%";
          window.setTimeout("countdown()", 1000);
      }
  }
  countdown();
</script>
<?php
// Below JS is to set the cheated input to 1 and submit form
// this is triggered when focus has been re-acquired.
?>
<script type="text/javascript">
  window.onfocus = function() {
    document.getElementById('cheated').value = 1;
    document.getElementById('submitTest').click();
  }
</script>
</html>
