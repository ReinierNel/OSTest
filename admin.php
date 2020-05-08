<?php
// DO NOT REMOVE
require('sql.php');
//New test Post
if (isset($_POST['newtest'])) {
  if ($_POST['newtest'] == 1) {
    //Removed validation for now need to fuguer out the regex first
    //if (preg_match("/^[a-zA-Z]+[a-zA-Z0-9\s._]+$/", $_POST['newTestName'])) {
      $newTestName = $_POST['newTestName'];
    //}
    //if (preg_match("/^[a-zA-Z]+[a-zA-Z0-9\s\.\,._]+$/", $_POST['newTestDescription'])) {
      $newTestDescription = $_POST['newTestDescription'];
    //}
    //if (preg_match("/^[0-9]+$/", $_POST['newTesTime'])) {
      $newTestTime = $_POST['newTesTime'];
    //}
    //Below check that we have all the vars needed to add a new test
    $nextInlineisCorrect = false;
    if (isset($newTestName) and isset($newTestDescription) and isset($newTestTime)) {
      if (newTest($newTestName, $newTestDescription, $newTestTime)) {
        //Get the newly added teset id from db
        $newTestID = getLastTestID();
        //Used to add the questions and awnser in the correct sequence
        $seqiencer = 0;
        //The following parses the names of each input field
        foreach ($_POST as $postKey => $postValue) {
          //check if nq (new question) is in the name
          if(strpos($postKey, "nq") !== false){
            //if true explode the name delimit by - and loop though
            $checkQuestionOrAwnser = explode("-", $postKey);

            //if na (new anwser) the we know we posted an awnser
            if (strpos($postKey, "na") !== false) {
              //check if the awnser is correct if yes run newAwnser function with isCorrect set to 1
              if (strpos($postKey, "na_correct") !== false) {
                $nextInlineisCorrect = $postValue;
              } else {
                if ($nextInlineisCorrect == $postKey) {
                  newAnswers((int)$newQuestionID, $seqiencer, $postValue, 1);
                } else {
                  newAnswers((int)$newQuestionID, $seqiencer, $postValue, 0);
                }
              }
              //update the sequicer
              $seqiencer = $seqiencer + 1;
              }  else {
              //add the question with the test id
              if (newQuestions((int)$newTestID, $postValue)) {
                $debugLog[] = "added a new Question to to db.<br>";
                // get newlay added question id from db this is used above with awnsers
                $newQuestionID = getLastQuestionID();
                $seqiencer = 0;
              }
            }
          }
        }
      }
    }
  }
}

//Delete Test POST deleteTest($testID)
if (isset($_POST['action'])) {
  if ($_POST['action'] == "rm-test") {
    deleteTest((int)$_POST['tid']);
  } elseif ($_POST['action'] == "update-test") {
    foreach ($_POST['question'] as $uqid => $uQuestion) {
      //Reset isCorrect
      resetIsCorrect((int)$uqid);
      //Update isCorrect
      updateIsCorrect((int)$uQuestion['correct'], 1);
      //Update Question Data
      updateQuestions((int)$uqid, $uQuestion['data']);
    }
    foreach ($_POST['awnsers'] as $uaid => $uAwnser) {
      updateAwnsers((int)$uaid, $uAwnser);
    }

    if (isset($_POST['updateTestName']) and isset($_POST['updateTestDescription']) and isset($_POST['updateTestTime']) and isset($_POST['tid'])) {
      updateTestDetails($_POST['tid'], $_POST['updateTestName'], $_POST['updateTestDescription'], $_POST['updateTestTime']);
    }

  } elseif ($_POST['action'] == "update-settings") {
    foreach ( getSettings() as $settingsNameCheck => $settingsValueCheck)
      if (isset($_POST[$settingsNameCheck])) {
        updateSetting($settingsNameCheck, $_POST[$settingsNameCheck]);
      }
  }
}
//get test details from db this sould be run last in the php block
$testDetails = getTest();
$testResults = complileAdminResultsByTest();
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
    <!--NAV START -->
    <div class="row sticky-top bg-light">
      <div class="col-sm-12">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tests"><i class="fas fa-tasks"></i> Manage Tests</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#results"><i class="fas fa-check-square"></i> View Results</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#settings"><i class="fas fa-cog"></i> Settings</a>
          </li>
        </ul>
        <div style="padding-bottom: 30px;">
        </div>
        <div class="row sticky-top bg-light">
          <div class="col-sm-12">
            <input class="form-control" id="search" type="text" placeholder="Search..">
          </div>
        </div>
        <div style="padding-bottom: 30px;">
        </div>
      </div>
    </div>
    <!-- NAV END -->
    <!-- STATIC MODAL START -->
    <!-- Add new Test Modal -->
      <div class="modal" id="newTest">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form method="post">
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Add a New Test</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h6>Test Details</h6>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Name: </span>
                  </div>
                  <input type="text" class="form-control" name="newTestName">
                </div>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Description: </span>
                  </div>
                  <input type="text" class="form-control" name="newTestDescription">
                </div>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Time: </span>
                  </div>
                  <input type="number" class="form-control" name="newTesTime">
                </div>
                <hr>
                <div id="qInput"></div>
                <button type="button" class="btn btn-primary" onclick="addQuestion()">Add question</button>
                <div style="padding-bottom: 30px;">
              </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="submit" name="newtest" value="1" class="btn btn-primary">Add New Test</button>
              <button type="button" onclick="resetQuestion()" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    <!-- STATIC MODAL END -->
    <!-- CONTENT START -->
    <div class="row">
        <div class="col-sm-12">
          <div class="tab-content">
            <!-- tests Content -->
            <div class="tab-pane container active" id="tests">
              <div class="row">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTest">
                    <i class="fas fa-plus-circle"></i> Add
                  </button>
                  <div style="padding-bottom: 30px;">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <table class="table table-hover" id="showTests">
                    <th data-toggle="tooltip" title="Click on the test name to expand the questions">Test Name</th><th>Test Description</th><th>Time</th><th>Manage</th><th>GoTo URL</th>
                    <?php
                    foreach ($testDetails as  $testData) {
                      $mgntTestInputs = '<h6>Details</h6>';
                      $mgntTestInputs .= '<div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Name: </span>
                        </div>
                        <input type="text" class="form-control" name="updateTestName" value="' . $testData['name'] . '">
                      </div>';
                      $mgntTestInputs .= '<div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Description: </span>
                        </div>
                        <input type="text" class="form-control" name="updateTestDescription" value="' . $testData['description'] . '">
                      </div>';
                      $mgntTestInputs .= '<div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Time: </span>
                        </div>
                        <input type="text" class="form-control" name="updateTestTime" value="' . $testData['time'] . '">
                      </div>';
                      $mgntTestInputs .= '<h6>Questions</h6>';
                      echo '<tr id="test">';
                      echo '<td data-toggle="collapse" data-target="#testContent' . $testData['id'] . '">' . $testData['name'] . '</td>
                      <td>' . $testData['description'] . '</td><td>' . $testData['time'] . '</td>
                      <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#testMgnt' . $testData['id'] . '">Manage</button></td>
                      <td><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#goto' . $testData['id'] . '">URL</button></td>';
                      echo '<tr><td colspan="4"><div id="testContent' . $testData['id'] . '" class="collapse">';
                      //question here
                      $testQuestions = compileTest((int)$testData['id']);
                      foreach ($testQuestions as $QAkey => $QAvalue) {
                        foreach ($QAvalue as $Qkey => $Qvalue) {
                          if ($Qkey != 'name' and $Qkey != 'description' and $Qkey != 'time') {
                            echo '<h6>' . $Qvalue['question'] . '</h6>';
                            $mgntTestInputs .= '<div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text">Question: </span>
                              </div>
                              <input type="text" class="form-control" name="question[' . $Qkey . '][data]" value="' . $Qvalue['question'] . '">
                            </div>';
                            foreach ($Qvalue['answers'] as $Akey => $Avalue) {
                              if (checkAwnser($Akey)) {
                                $mgntTestInputs .= '<div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">Awnser: </span>
                                    <span class="input-group-text"><input type="radio" name="question[' . $Qkey . '][correct]" value="' . $Akey . '" checked></span>
                                  </div>
                                  <input type="text" class="form-control" name="awnsers[' . $Akey . ']" value="' . $Avalue . '">
                                </div>';
                                echo '<p>' . $Avalue . ' <i class="fas fa-check-square"></i></p>';
                              } else {
                                $mgntTestInputs .= '<div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">Awnser: </span>
                                    <span class="input-group-text"><input type="radio" name="question[' . $Qkey . '][correct]" value="' . $Akey . '"></span>
                                  </div>
                                  <input type="text" class="form-control" name="awnsers[' . $Akey . ']" value="' . $Avalue . '">
                                </div>';
                                echo '<p>' . $Avalue . ' <i class="fas fa-cross-square"></i></p>';
                              }
                            }
                          }
                        }
                      }
                      echo '</div></td></tr>';
                      echo '</tr>';
                      echo '<!-- Manage Test Modal -->
                        <div class="modal" id="testMgnt' . $testData['id'] . '">
                          <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                              <form method="post">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">Manage ' . $testData['name'] . '</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <!-- Modal body -->
                              <div class="modal-body">
                              <p class="text-danger">Updating Questions might make previous test results inaccurate</p>
                                ' . $mgntTestInputs . '
                              </div>
                              <input type="hidden" name="tid" value="' . $testData['id'] . '">
                              <!-- Modal footer -->
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" name="action" value="rm-test">Delete Test</button>
                                <button type="submit" class="btn btn-primary" name="action" value="update-test">Update Test</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              </div>
                              </form>
                            </div>
                          </div>
                        </div>';
                        echo '<!-- Goto Test Modal -->
                          <div class="modal" id="goto' . $testData['id'] . '">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                  <h4 class="modal-title">GoTo URL for ' . $testData['name'] . '</h4>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">

                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">Name</span>
                                  </div>
                                  <input type="text" class="form-control" onchange="gotourlName(this.value)">
                                </div>

                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">Emails</span>
                                  </div>
                                  <input type="text" class="form-control" onchange="gotourlEmail(this.value)">
                                </div>
                                <h6>Direct Access URL</h6>
                                <code>
                                http://' . $_SERVER['SERVER_NAME'] . chop($_SERVER['REQUEST_URI'],"admin.php") . 'goto.php?name=<span id=gotourlname></span>&email=<span id=gotourlemail></span>&tid=' . $testData['id'] . '
                                </code>

                                </div>
                                <!-- Modal footer -->
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>';
                    }
                    ?>
                  </table>
                </div>
              </div>
            </div>
            <!-- results content -->
            <div class="tab-pane container fade" id="results">
              <!-- Nav pills -->
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane container active" id="testResults">
                  <div style="padding-bottom: 20px;">
                  </div>
                  <?php
                  $viewResultsModal = '';
                  foreach ($testResults as $TestLists) {
                    if (is_array($TestLists)) {
                      foreach ($TestLists as $participantList) {
                        $rtid = $participantList['id'];
                        echo '<div id="test">';
                        echo '<h4>' . $participantList['name'] . '</h4>';
                        echo '<p class="text-secondary"><small>' . $participantList['description'] . '</small></p>';
                        echo '<table class="table">';
                        echo '<th>Name</th><th>Email</th><th>Date</th><th>Time</th><th>Score</th><th>Correct</th><th>Wrong</th><th>Cheated</th><th>Anwsers</th>';
                        if (is_array($participantList['partispans'])) {
                          foreach ($participantList['partispans'] as $questionsList) {
                            if (isset($questionsList['name']) and isset($questionsList['email'])) {
                              echo '<tr>';
                              echo '<td>' . $questionsList['name'] . '</td>';
                              echo '<td>' . $questionsList['email'] . '</td>';
                              echo '<td>' . $questionsList['date_start'] . '</td>';
                              echo '<td>' . $questionsList['time'] . '</td>';
                              if (isset($questionsList['score'])) {
                                echo '<td>' . $questionsList['score'] . '</td>';
                              } else {
                                echo '<td></td>';
                              }
                              if (isset($questionsList['correctQuestions'])) {
                                echo '<td>' . $questionsList['correctQuestions'] . '</td>';
                              }  else {
                                echo '<td></td>';
                              }
                              if (isset($questionsList['wrongQuestions'])) {
                                echo '<td>' . $questionsList['wrongQuestions'] . '</td>';
                              } else {
                                echo '<td></td>';
                              }
                              if (isset($questionsList['cheated'])) {
                                echo '<td>' . $questionsList['cheated'] . '</td>';
                              } else {
                                echo '<td></td>';
                              }
                              echo '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewResults' . $questionsList['id'] . '">View</button></td>';
                              echo '</tr>';
                            }

                            $viewResultsModal .= '<!-- The Modal -->
                              <div class="modal" id="viewResults' . $questionsList['id'] . '">
                                <div class="modal-dialog modal-xl">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Results for ' . $questionsList['name'] . ' ' . $questionsList['email'] . '</h4>
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                    <table class="table">
                                    <th>Question</th><th>Awnser</th>';
                            if (isset($questionsList['questions'])) {
                              foreach ($questionsList['questions'] as $AwnsertsList) {
                                $viewResultsModal .= '<tr>';
                                $viewResultsModal .= '<td><small>' . $AwnsertsList['question'] . '</small></td>';
                                foreach ($AwnsertsList['answers'] as $checkIfCorrect) {
                                  if ($checkIfCorrect['correct'] === true) {
                                    $viewResultsModal .= '<td class="text-success">' .  $checkIfCorrect['answers'] . '</td>';
                                  } else {
                                    $viewResultsModal .= '<td class="text-danger">' .  $checkIfCorrect['answers'] . '</td>';
                                  }
                                }
                                $viewResultsModal .= '</tr>';
                              }
                            }


                            $viewResultsModal .= '</table>
                            </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  </div>

                                </div>
                              </div>
                            </div>';
                          }
                        }

                        echo '</table>';
                        echo '</div>';

                      }
                    }
                  }
                  echo $viewResultsModal;
                  ?>
                </div>
                <div class="tab-pane container fade" id="participants">
                </div>
                <div class="tab-pane container fade" id="passed">
                  <p>feature not available yet.</p>
                </div>
                <div class="tab-pane container fade" id="failed">
                  <p>feature not available yet.</p>
                </div>
              </div>




            </div>
            <!-- Settings content -->
            <div class="tab-pane container fade" id="settings">
              <?php $settingsData = getSettings();
              echo '<form method="post">';
              foreach ($settingsData as $settingsName => $settingsValue) {
                echo '<div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">' . $settingsName . ': </span>
                        </div>
                        <input type="text" class="form-control" name="' . $settingsName . '" value="' . $settingsValue . '">
                      </div>';
              }
              echo '<button type="submit" name="action" value="update-settings" class="btn btn-primary">Update</button>';
              echo '</form>';
              ?>
            </div>
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
    var_dump($participantList);
    echo '</pre>';
    echo '<pre>';
    echo var_dump(get_defined_vars());
    //echo var_dump($_POST);
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
  <script type="text/javascript">
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
  <!-- new test form JS -->
  <script type="text/javascript">
  //increment ids and names
  var i = 0;
  var a = 0;
  function increment(){
    i += 1;
  }
  function aincrement(){
    a += 1;
  }
  //Remove someting
  function removeElement(parentDiv, childDiv){
    if (childDiv == parentDiv){
      alert("The parent div cannot be removed.");
    }
    else if (document.getElementById(childDiv)){
      var child = document.getElementById(childDiv);
      var parent = document.getElementById(parentDiv);
      parent.removeChild(child);
    }
    else{
      alert("Child div has already been removed or does not exist.");
    return false;
    }
  }
  //Adds new  quistions field
  function addQuestion() {
    //Setup HTML elements
    var groupQ  = document.createElement('div');
    var inputGroupQ = document.createElement('div');
    var inputGroupPrependQ = document.createElement('div');
    var prependQName = document.createElement('span');
    var prependQControl1 = document.createElement('span')
    var prependQControl1Icon = document.createElement('i');
    var prependQControl2 = document.createElement('span')
    var prependQControl2Icon = document.createElement('i');
    var qInput = document.createElement('input');
    //Setup classes
    groupQ.setAttribute("id", "id_" + i);
    inputGroupQ.setAttribute("class", "input-group mb-3");
    inputGroupPrependQ.setAttribute("class", "input-group-prepend");
    prependQName.setAttribute("class", "input-group-text");
    prependQControl1.setAttribute("class", "input-group-text");
    prependQControl1Icon.setAttribute("class", "fas fa-minus-circle");
    prependQControl1Icon.setAttribute("onclick", "removeElement('qInput','id_" + i + "')");
    prependQControl2.setAttribute("class", "input-group-text");
    prependQControl2Icon.setAttribute("class", "fas fa-plus-circle");
    prependQControl2Icon.setAttribute("onclick", "newAnswer('aInput','aid_" + i + "')");
    //Set PREPEND Text
    prependQName.textContent = "Question";
    //Set input
    inputGroupQ.setAttribute("id", "id_" + i);
    qInput.setAttribute("name", "nq_" + i);
    qInput.setAttribute("class", "form-control");
    qInput.setAttribute("type", "text");
    //Increment with 1
    increment();
    a = 0;
    //Build HTML Elemets
    groupQ.appendChild(inputGroupQ);
    inputGroupQ.appendChild(inputGroupPrependQ);
    inputGroupPrependQ.appendChild(prependQName);
    inputGroupQ.appendChild(prependQControl2);
    prependQControl2.appendChild(prependQControl2Icon);
    inputGroupQ.appendChild(prependQControl1);
    prependQControl1.appendChild(prependQControl1Icon);
    inputGroupQ.appendChild(qInput);
    //Send to Div
    document.getElementById("qInput").appendChild(groupQ);
  }
  //Adds new anwser input field
  function newAnswer() {
    //remove one to componsate fot above i increment
    i -= 1;
    //Setup HTML elements
    var groupQ = document.getElementById("id_" + i);
    var inputGroupA = document.createElement('div');
    var inputGroupPrependA = document.createElement('div');
    var prependAName = document.createElement('span');
    var prependAControl1 = document.createElement('span')
    var prependAControl1Icon = document.createElement('i');
    var prependAControl2 = document.createElement('span')
    var prependAControl2Icon = document.createElement('input');
    var aInput = document.createElement('input');
    //Setup classes
    inputGroupA.setAttribute("class", "input-group mb-3");
    inputGroupA.setAttribute("id", "aid");
    inputGroupPrependA.setAttribute("class", "input-group-prepend");
    prependAName.setAttribute("class", "input-group-text");
    prependAControl1.setAttribute("class", "input-group-text");
    prependAControl1Icon.setAttribute("class", "fas fa-minus-circle");
    prependAControl1Icon.setAttribute("onclick", "removeElement('id_" + i + "','id_" + i + a + "')");
    prependAControl2.setAttribute("class", "input-group-text");
    prependAControl2Icon.setAttribute("type", "radio");
    prependAControl2Icon.setAttribute("name", "nq_" + i + "-na_correct");
    prependAControl2Icon.setAttribute("value", "nq_" + i + "-na_" + a);
    //Set PREPEND Text
    prependAName.textContent = "Awnser";
    //Set input
    inputGroupA.setAttribute("id", "id_" + i + a);
    aInput.setAttribute("name", "nq_" + i + "-na_" + a);
    aInput.setAttribute("class", "form-control");
    aInput.setAttribute("type", "text");
    //Increment
    aincrement()
    //Build HTML Elemets
    groupQ.appendChild(inputGroupA);
    inputGroupA.appendChild(inputGroupPrependA);
    inputGroupPrependA.appendChild(prependAName);
    inputGroupA.appendChild(prependAControl1);
    prependAControl1.appendChild(prependAControl1Icon);
    inputGroupA.appendChild(prependAControl2);
    prependAControl2.appendChild(prependAControl2Icon);
    inputGroupA.appendChild(aInput);
    //Send to Div
    document.getElementById("qInput").appendChild(groupQ);
    i += 1;
  }
  //clear dinamic inputs
  function resetQuestion(){
  document.getElementById('qInput').innerHTML = '';
  }
  </script>
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
  <!-- GoTo URL -->
  <script type="text/javascript">
    function gotourlName(name) {
      var urlname = encodeURI(name);
      document.getElementById('gotourlname').innerHTML = urlname;
    }
    function gotourlEmail(email) {
      var urlemail = encodeURI(email);
      document.getElementById('gotourlemail').innerHTML = urlemail;
    }
  </script>
</body>
</html>
