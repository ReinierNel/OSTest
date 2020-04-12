<?php
require('sql.php');
//Handle GET
if (isset($_GET['rewrite'])) {
  if (empty($_GET[rewrite])) {
    $cantRewrite = 1;
  } else {
    $cantRewrite = 0;
  }
} else {
  $cantRewrite = 0;
}
//Handle POST
if (isset($_POST['testName']) and !empty($_POST['testName'])) {
  $testName = $_POST['testName'];
}
if (isset($_POST['testID']) and !empty($_POST['testID'])) {
  if (is_int((int)$_POST['testID'])) {
    $tid = (int)$_POST['testID'];
  }
}
if (isset($_POST['name']) and !empty($_POST['name'])) {
  if (preg_match("/^[a-zA-Z-\040]+$/", $_POST['name'])) {
    $participantName = $_POST['name'];
  }
}
if (isset($_POST['email']) and !empty($_POST['email'])) {
  if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $participantEmail = $_POST['email'];
  }
}
if (isset($_POST['cheated'])) {
  $cheated = $_POST['cheated'];
} else {
  $cheated = 1;
}
if (isset($_POST['question']) and is_array($_POST['question'])) {
  $participantsAwnsers = $_POST['question'];
} else {
  $participantsAwnsers[] = false;
}
if (isset($_POST['participantID']) and !empty($_POST['participantID'])) {
    $pid = $_POST['participantID'];
}
//Check all posts have passed validation and cheated is 0
if (isset($testName) and isset($tid) and isset($participantName) and isset($participantEmail) and $cheated == 0 and isset($participantsAwnsers) and isset($pid)) {
    //Update Awnsers
    $getCompletionDate = getParticipantsFromCompleted((int)$tid, (int)$pid);
    if (isset($getCompletionDate['id'])) {
      $debugLog[] = "Test already marked as complted";
    } else {
      //Mark as complete
      if (completeTest((int)$pid, (int)$tid, 0)) {
        $debugLog[] = "Marking test as completed";
      }
      foreach ($participantsAwnsers as $qid => $aid) {
        if (newMarks((int)$pid, (int)$aid, (int)$qid, (int)$tid) == true) {
          $debugLog[] = 'adding mark for awnser ID: ' . $aid . ' to question ID: ' . $qid;
        }
      }
    }
    //Complile results
    $results = compileTestResults((int)$pid, (int)$tid);
} elseif ($cheated == 1) {
  if (isset($pid) and isset($tid)) {
    if (completeTest((int)$pid, (int)$tid, 1)) {
      $debugLog[] = "Marking test as completed and chated";
    }
  }
}
// Check that the test can be re written and set headings
if ($cantRewrite == 1) {
  $bodyHeading = '<h1>Sorry it seems like you have already writen this test.</h1>';
  $bodyHeading .= '<p>The people over at ' . siteName . ' does not allow test rewrites.</p>';
} elseif ($cheated == 1) {
  $bodyHeading = '<h1>Sorry it seems like you cheated on this test.</h1>';
  $bodyHeading .= '<p>You can not click away or minimize the test this will result in a test beeing marked as cheated, please contatc ' . siteName . ' if you feel this was in error.</p>';
} else {
  $bodyHeading = '<h1>Thank you for your participation</h1><div style="padding-bottom: 30px;"></div>';
  //Summary
  if (isset($results)) {
    foreach ($results as $output => $value) {
      if ($output == 'Total') {
        $total = $value;
      } elseif ($output == 'Currect') {
        $correct = $value;
      } elseif ($output == 'Wrong') {
        $wrong = $value;
      } elseif ($output == 'Score') {
        $score =$value;
      } else {
        $resultDetails[$output] = $value;
      }
    }
  }
}
  if (isset($_POST['startTime'])) {
    $startTime = $_POST['startTime'];
  }
  if (isset($getCompletionDate['date_created'])) {
    $endTime = $getCompletionDate['date_created'];
  } else {
    $endTime = false;
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
          <?php
          if ($cantRewrite == 1) {
            echo '<h1>' . siteName . '</h1>';
            echo '<p>' . siteDescription . '</p>';
          } else {
            if (isset($_POST['testName'])) {
              echo '<h1>' . $_POST['testName'] . '</h1>';
              echo '<p>Test Results for ' . $_POST['name'] . ' ' . $_POST['email'] . '</p>';
            }
          }
          ?>
        </div>
      </div>
    </div>
    <!-- BANNER END -->
    <!-- CONTENT START -->
    <div class="row">
        <div class="col-sm-12">
          <?php
            echo $bodyHeading;
            // if the score are set build the test score html
            if (isset($score)) {
              if ($score >= passMark) {
                echo '<h4 class="text-success">You Passed</h4><div style="padding-bottom: 30px;"></div>';
              } else {
                echo '<h4 class="text-danger">You Failed, You need to get ' . passMark . '% to pass this test.</h4><div style="padding-bottom: 30px;"></div>';
              }
            }
            if ($cheated == 0 and isset($total)) {
              //Summary table
              echo '<h4>Summary</h4>
              <table class="table">
                <tr>
                  <td>Total Question</td><td>' . $total . '</td>
                </tr>
                <tr>
                  <td>Correct Answers</td><td>' . $correct . '</td>
                </tr>
                <tr>
                  <td>Wrong Answers</td><td>' . $wrong . '</td>
                </tr>
                <tr>
                  <td>Your Score</td><td>' . (int)$score . '%</td>
                </tr>
                <tr>
                  <td>Score Required</td><td>' . passMark . '%</td>
                </tr>
              </table><div style="padding-bottom: 30px;"></div>';
              //Details Table
              if (resultsDetails == 1) {
                echo '<h4>Details</h4>
                <table class="table">';
                echo '<th>Question</th><th>Your Awnser</th><th>Status</th>';
                foreach ($resultDetails as $question => $answers) {
                  echo '<tr><td>' . $question  . '</td>';
                  foreach ($answers as $answer => $mark) {
                    if ($mark == 'Correct') {
                      echo '<td class="text-success">' . $answer  . '</td>';
                      echo '<td class="text-success">' . $mark  . '</td>';
                    } else {
                      echo '<td class="text-danger">' . $answer  . '</td>';
                      echo '<td class="text-danger">' . $mark  . '</td>';
                    }
                  }
                  echo '</tr>';
                }
                echo '</table><div style="padding-bottom: 30px;"></div>';
              }
            }
            //Results page instructions
            echo '<p class="text-secondary"><small>' . resultsInstructions . '</small></p>';
            ?>
          <a href="./" type="button" class="btn btn-primary">Go Back</a>
          <div style="padding-bottom: 30px;">
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
</html>
