<?php
//DB Settings Edit this
$sqlServer = "[MYSQL SERVER ADDRESS]";
$sqlUser = "[DATABASE USER]";
$sqlPassword = "[DATABASE PASSWORD]";
$sqlDatabase = "[DATABASE NAME]";
//Dont Edit enyting from this point down.
//Create Table SQL
$createTables = array (
  'test' => "CREATE TABLE IF NOT EXISTS `$sqlDatabase`.`test` ( `id` INT NOT NULL AUTO_INCREMENT , `name` TEXT NOT NULL , `description` TEXT NOT NULL , `time` INT NOT NULL , `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB",
  'questions' => "CREATE TABLE IF NOT EXISTS `$sqlDatabase`.`questions` ( `id` INT NOT NULL AUTO_INCREMENT , `test_id` INT NOT NULL , `questions` TEXT NOT NULL , `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB",
  'answers' => "CREATE TABLE IF NOT EXISTS `$sqlDatabase`.`answers` ( `id` INT NOT NULL AUTO_INCREMENT , `questions_id` INT NOT NULL , `sequence` INT NOT NULL , `answers` TEXT NOT NULL , `is_correct` TINYINT NOT NULL , `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB",
  'participants' => "CREATE TABLE IF NOT EXISTS `$sqlDatabase`.`participants` ( `id` INT NOT NULL AUTO_INCREMENT , `name` TEXT NOT NULL , `email` TEXT NOT NULL , `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `test_id` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB",
  'makrs' => "CREATE TABLE IF NOT EXISTS `$sqlDatabase`.`marks` ( `id` INT NOT NULL AUTO_INCREMENT , `participants_id` INT NOT NULL , `answers_id` INT NOT NULL , `questions_id` INT NOT NULL , `test_id` INT NOT NULL , `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB",
  'settings' => "CREATE TABLE `$sqlDatabase`.`settings` ( `name` TEXT NOT NULL , `value` TEXT NOT NULL , UNIQUE `settings_name` (`name`)) ENGINE = InnoDB",
  'complete' => "CREATE TABLE `$sqlDatabase`.`complete` ( `id` INT NOT NULL AUTO_INCREMENT , `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `participants_id` INT NOT NULL , `test_id` INT NOT NULL , `cheated` TINYINT NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE = InnoDB"
);
//Constraints between tables SQL
$createConstraints = array (
  'questions_test' => "ALTER TABLE `questions` ADD CONSTRAINT `question_test` FOREIGN KEY (`test_id`) REFERENCES `test`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'answers_questions' => "ALTER TABLE `answers` ADD CONSTRAINT `answers_questions` FOREIGN KEY (`questions_id`) REFERENCES `questions`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'participants_test' => "ALTER TABLE `participants` ADD CONSTRAINT `participants_test` FOREIGN KEY (`test_id`) REFERENCES `test`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'marks_answers' => "ALTER TABLE `marks` ADD CONSTRAINT `marks_answers` FOREIGN KEY (`answers_id`) REFERENCES `answers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'marks_questions' => "ALTER TABLE `marks` ADD CONSTRAINT `marks_questions` FOREIGN KEY (`questions_id`) REFERENCES `questions`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'marks_test' => "ALTER TABLE `marks` ADD CONSTRAINT `marks_test` FOREIGN KEY (`test_id`) REFERENCES `test`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'marks_participants' => "ALTER TABLE `marks` ADD CONSTRAINT `marks_participants` FOREIGN KEY (`participants_id`) REFERENCES `participants`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'complete_participants' => "ALTER TABLE `complete` ADD CONSTRAINT `complete_participants` FOREIGN KEY (`participants_id`) REFERENCES `participants`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
  'complete_test' => "ALTER TABLE `complete` ADD CONSTRAINT `complete_test` FOREIGN KEY (`test_id`) REFERENCES `test`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT"
);
//settings data and also dummy data
$createSettings = array (
  'setupDB' => "INSERT INTO `settings` (`name`, `value`) VALUES ('setupDB', '0')",
  'debug' => "INSERT INTO `settings` (`name`, `value`) VALUES ('debug', '0')",
  'rewrite' => "INSERT INTO `settings` (`name`, `value`) VALUES ('rewrite', '0')",
  'siteName' => "INSERT INTO `settings` (`name`, `value`) VALUES ('siteName', 'OSTest')",
  'siteDescription' => "INSERT INTO `settings` (`name`, `value`) VALUES ('siteDescription', 'Open Source Testing Application')",
  'passMakr' => "INSERT INTO `settings` (`name`, `value`) VALUES ('passMark', '50')",
  'resultsDetails' => "INSERT INTO `settings` (`name`, `value`) VALUES ('resultsDetails', '1')",
  'testInstruction' => "INSERT INTO `settings` (`name`, `value`) VALUES ('testInstruction', 'Once the time for the test has hit 0 it will automatically submit. Clicking away or using ALT + TAB will result in a test marked as cheated.')",
  'resultsInstructions' => "INSERT INTO `settings` (`name`, `value`) VALUES ('resultsInstructions', 'Your test results are saved, Click on go back to do another test.')"
);
//example data for testing. and also not to error out the script when setting up the db for first time
$demoData = array (
  'demoTest' => "INSERT INTO `test` (`id`, `name`, `description`, `time`, `date_created`) VALUES (NULL, 'Demo Test', 'This is a demo test. You have 30 minutes to complete 5 questions have fun and good luck.', '30', current_timestamp())",
  'demoQuestions1' => "INSERT INTO `questions` (`id`, `test_id`, `questions`, `date_created`) VALUES (NULL, '1', 'The average person does what thirteen times a day?', current_timestamp())",
  'demoAnswers1-1' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '1', '1', 'Laughs', '1', current_timestamp())",
  'demoAnswers1-2' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '1', '2', 'Eat fried chicken', '0', current_timestamp())",
  'demoAnswers1-3' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '1', '3', 'Water Skiers', '0', current_timestamp())",
  'demoAnswers1-4' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '1', '4', 'Opens Fridge', '0', current_timestamp())",
  'demoQuestions2' => "INSERT INTO `questions` (`id`, `test_id`, `questions`, `date_created`) VALUES (NULL, '1', 'How much did the first three minutes of a call cost when commercial telephone service was introduced between New York and London in 1927?', current_timestamp())",
  'demoAnswers2-1' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '2', '1', '100.00 USD', '0', current_timestamp())",
  'demoAnswers2-2' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '2', '2', '50.00 USD', '0', current_timestamp())",
  'demoAnswers2-3' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '2', '3', '75.00 USD', '1', current_timestamp())",
  'demoAnswers2-4' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '2', '4', '14.00 USD', '0', current_timestamp())",
  'demoQuestions3' => "INSERT INTO `questions` (`id`, `test_id`, `questions`, `date_created`) VALUES (NULL, '1', 'For whom was the Mercedes automobile named?', current_timestamp())",
  'demoAnswers3-1' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '3', '1', 'German automaker Gottlieb Daimler Wife', '0', current_timestamp())",
  'demoAnswers3-2' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '3', '2', 'German automaker Gottlieb Daimler Sister', '0', current_timestamp())",
  'demoAnswers3-3' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '3', '3', 'German automaker Gottlieb Daimler Mother', '0', current_timestamp())",
  'demoAnswers3-4' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '3', '4', 'German automaker Gottlieb Daimler Daughter', '1', current_timestamp())",
  'demoQuestions4' => "INSERT INTO `questions` (`id`, `test_id`, `questions`, `date_created`) VALUES (NULL, '1', 'In 1996, which countrys army became the last in the world to disband its carrier pigeon service?', current_timestamp())",
  'demoAnswers4-1' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '4', '1', 'Japan', '0', current_timestamp())",
  'demoAnswers4-2' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '4', '2', 'Switzerland', '1', current_timestamp())",
  'demoAnswers4-3' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '4', '3', 'South Africa', '0', current_timestamp())",
  'demoAnswers4-4' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '4', '4', 'Mexico', '0', current_timestamp())",
  'demoQuestions5' => "INSERT INTO `questions` (`id`, `test_id`, `questions`, `date_created`) VALUES (NULL, '1', 'Which country was ruled by the Romanov dynasty 1613-1917?', current_timestamp())",
  'demoAnswers5-1' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '5', '1', 'Salvador Allende', '0', current_timestamp())",
  'demoAnswers5-2' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '5', '2', 'Russia', '1', current_timestamp())",
  'demoAnswers5-3' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '5', '3', 'French', '0', current_timestamp())",
  'demoAnswers5-4' => "INSERT INTO `answers` (`id`, `questions_id`, `sequence`, `answers`, `is_correct`, `date_created`) VALUES (NULL, '5', '4', 'Germany', '0', current_timestamp())",
);
//Create tables and Constraints
$sqlConnectStart = mysqli_connect($sqlServer, $sqlUser, $sqlPassword, $sqlDatabase);
// connection
if (!$sqlConnectStart) {
  $debugLog['sql'][] = mysqli_connect_error();
} else {
  $debugLog[] = 'SQL Connected Successfully';
}
//Get Settings
$getSettingsSQL = "SELECT name, value FROM `settings`";
$querySettingsSQL = mysqli_query($sqlConnectStart, $getSettingsSQL);
if (mysqli_num_rows($querySettingsSQL) > 0) {
  while ($settings = mysqli_fetch_assoc($querySettingsSQL)) {
    define($settings['name'], $settings['value']);
  }
} else {
  // If no settings can be collected asume new setup and run settup  DB code
    $debugLog[] = "no Settings found !WARNING! new tables might be created now.";
    define("setupDB", '1');
    define("debug", '1');
}
//Setup DB
if (setupDB != 0) {
  set_time_limit(360);
  //Create Tables
  foreach ($createTables as $tableName => $tableSQL) {
    if (mysqli_query($sqlConnectStart, $tableSQL)) {
      $debugLog[] = 'Table ' . $tableName . " Created Successfully";
    } else {
      $debugLog[] = mysqli_error($sqlConnect);
    }
  }
  //Create Constaints
  foreach ($createConstraints as $constraintName => $constraintSQL) {
    if (mysqli_query($sqlConnectStart, $constraintSQL)) {
      $debugLog[] = 'Constaint' . $constraintName . " Created Successfully";
    } else {
      $debugLog[] = mysqli_error($sqlConnectStart);
    }
  }
  //Create Settings
  foreach ($createSettings as $settingsName => $settingsSQL) {
    if (mysqli_query($sqlConnectStart, $settingsSQL)) {
      $debugLog[] = 'Settings' . $settingsName . " Created Successfully";
    } else {
      $debugLog[] = mysqli_error($sqlConnectStart);
    }
  }
  //Create demo data
  foreach ($demoData as $dataName => $dataSQL) {
    if (mysqli_query($sqlConnectStart, $dataSQL)) {
      $debugLog[] = 'Demo Data ' . $dataName . " Created Successfully";
    } else {
      $debugLog[] = mysqli_error($sqlConnectStart);
    }
  }
  //
  die(header("Refresh:0"));
}
//START SQL Functions
//net tets insert function
function newTest($testName, $testDescription, $testTime) {
  //Removed validtation from function, will add validation on before the function is called
  global $debugLog, $sqlConnectStart;
  //validate inputs
  //if (preg_match("/^[a-zA-Z]+[a-zA-Z0-9\s._]+$/", $testName)) {
  //  $name = $testName;
  //}
  //if (preg_match("/^[a-zA-Z]+[a-zA-Z0-9\s\.\,._]+$/", $testDescription)) {
  //  $description = $testDescription;
  //}
  //if (preg_match("/^[0-9]+$/", $testTime)) {
  //  $time = $testTime;
  //}
  // Check that the vars are set if true then run sql
  //if (isset($name) and isset($description) and isset($time)) {
    //run sql
    $sql = "INSERT INTO test (name, description, time) VALUES ('$testName', '$testDescription', '$testTime')";
    if (mysqli_query($sqlConnectStart, $sql)) {
      return true;
    } else {
      return false;
    }
  //} else {
  //  return false;
  //}
}
//new question insert function
function newQuestions($testID, $theQuestion) {
  global $debugLog, $sqlConnectStart;
  //validate inputs
  if (is_int($testID)) {
    $id = $testID;
  }
  //if (preg_match("/^[a-zA-Z]+[a-zA-Z0-9\s\.\,\?._]+$/", $theQuestion)) {
    $question = $theQuestion;
  //}
  if (isset($id) and isset($question)) {
    //run sql
    $sql = "INSERT INTO questions (test_id, questions) VALUES ('$id', '$theQuestion')";
    if (mysqli_query($sqlConnectStart, $sql)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
//new awsner insert sql
function newAnswers($questionID, $seqience, $answers, $isCorrect) {
  global $debugLog, $sqlConnectStart;
  //validate inputs
  if (is_int($questionID)) {
    $id = $questionID;
  }
  if (is_int($seqience)) {
    $seq = $seqience;
  }
  if (is_int($isCorrect)) {
    $correct = $isCorrect;
  }
  //if (preg_match("/^[a-zA-Z]+[a-zA-Z0-9\s\.\,\?._]+$/", $answers)) {
    $theAnswers = $answers;
  //}
  if (isset($id) and isset($seq) and isset($correct) and isset($theAnswers)) {
    //run sql
    $sql = "INSERT INTO answers (questions_id, sequence, answers, is_correct) VALUES ('$id', '$seq', '$theAnswers', '$correct')";
    if (mysqli_query($sqlConnectStart, $sql)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
//new partisipant insert function
function newParticipants($testID, $name, $email) {
  global $debugLog, $sqlConnectStart;
  //validate inputs
  if (is_int($testID)) {
    $id = $testID;
  }
  if (preg_match("/^[a-zA-Z-\040]+$/", $name)) {
    $usersName = $name;
  }
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $usersEmail = $email;
  }

  if (isset($id) and isset($usersName) and isset($usersEmail)) {
    //run sql
    $sql = "INSERT INTO participants (name, email, test_id) VALUES ('$usersName', '$usersEmail', '$id')";
    if (mysqli_query($sqlConnectStart, $sql)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
// new marks insert function
function newMarks($participantsID, $answersID, $questionsID, $testID) {
  global $debugLog, $sqlConnectStart;
  //validate inputs
  if (is_int($participantsID)) {
    $pid = $participantsID;
  }
  if (is_int($answersID)) {
    $aid = $answersID;
  }
  if (is_int($questionsID)) {
    $qid = $questionsID;
  }
  if (is_int($testID)) {
    $tid = $testID;
  }
  if (isset($pid) and isset($aid) and isset($qid) and isset($tid)) {
    //run sql
    $sql = "INSERT INTO marks (participants_id, answers_id, questions_id, test_id) VALUES ('$pid', '$aid', '$qid', '$tid')";
    if (mysqli_query($sqlConnectStart, $sql)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
//update settings insert function
function updateSetting($settingsName, $settingsValue) {
  global $debugLog, $sqlConnectStart;
  if (preg_match("/^[a-zA-Z]+$/", $settingsName)) {
    $name = $settingsName;
  }
  if (preg_match("/^[a-zA-Z0-9]+$/", $settingsValue)) {
    $value = $settingsValue;
  }
  // Check that the vars are set if true then run sql
  if (isset($name) and isset($value)) {
    //run sql
    $sql = "SELECT name FROM settings WHERE name = '$name'";
    $result = mysqli_query($sqlConnectStart, $sql);
    if (mysqli_num_rows($result) > 0) {
      $sqlUpdate = "UPDATE settings SET value = '$value' WHERE name = '$name'";
      if (mysqli_query($sqlConnectStart, $sqlUpdate)) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  } else {
    return false;
  }
}
// complile test from db to array
function compileTest($testID) {
  global $debugLog, $sqlConnectStart;
  //Check that the testID is an int other
  if (is_int($testID)) {
    $tid = $testID;
  }
  // if tid is not set dont interface with db
  if (isset($tid)) {
      //Get Test
      $getTestSql = "SELECT id, name, description, time FROM test WHERE id = $tid";
      $getTestResults = mysqli_query($sqlConnectStart, $getTestSql);
      $getTestRow = mysqli_fetch_assoc($getTestResults);
      //Start building the aboutput as an array
      $fullTest[$getTestRow['id']]['name'] = $getTestRow['name'];
      $fullTest[$getTestRow['id']]['description'] = $getTestRow['description'];
      $fullTest[$getTestRow['id']]['time'] = $getTestRow['time'];
      //Get question
      $getQuestionsSQL = "SELECT id, questions FROM questions WHERE test_id = '$tid' ORDER BY id ASC";
      $getQuestionsResults = mysqli_query($sqlConnectStart, $getQuestionsSQL);
      if (mysqli_num_rows($getQuestionsResults) > 0) {
        while($getQuestionsRows = mysqli_fetch_assoc($getQuestionsResults)) {
          $qid = $getQuestionsRows['id'];
          //Get awnserts
          $getAnsewersSQL = "SELECT id, answers, is_correct FROM answers WHERE questions_id = '$qid' ORDER BY sequence ASC";
          $getAnsewersResults = mysqli_query($sqlConnectStart, $getAnsewersSQL);
          while ($getAnsewersRow = mysqli_fetch_assoc($getAnsewersResults)) {
              //Adding the quistions to rhe array as an array
              $fullTest[$getTestRow['id']][$getQuestionsRows['id']]['question'] = $getQuestionsRows['questions'];
              //Adding the awnsers of the quistions as an array of the quistions array in the test array
              $fullTest[$getTestRow['id']][$getQuestionsRows['id']]['answers'][$getAnsewersRow['id']] = $getAnsewersRow['answers'];
          }
        }
      }
      return $fullTest;
  } else {
    return false;
  }
  /* Test Array Details for your sanity
  array(1) {
    [1]=> <------------------------------------- Test ID
    array(5) {
      ["name"]=>
      string(11) "Test" <----------------------- Test Name
      ["description"]=>
      string(39) "This test is a test" <-------- Test Description
      [1]=> <----------------------------------- Question ID
      array(2) {
        ["question"]=>
        string(41) "can you count to five?" <---- Question Text
        ["answers"]=>
        array(4) {
          [1]=> <-------------------------------- Awnser ID
          string(8) "Yes?" <-------------------- Awnser Text
          [2]=> <------------------------------- Awnser ID
          string(8) "No?" <--------------------- Awnser Text
        }
      }
  */
}
//not used at the moment table does not exist leaving it here for later.
function completeTest($participantsID, $testID, $cheated) {
  global $debugLog, $sqlConnectStart;
  //validate inputs
  if (is_int($participantsID)) {
    $pid = $participantsID;
  }
  if (is_int($testID)) {
    $tid = $testID;
  }
  if (isset($cheated)) {
    if (is_int($cheated)) {
      $setCheated = $cheated;
    } else {
      $setCheated = 0;
    }
  } else {
      $setCheated = 0;
    }
  if (isset($pid) and isset($tid)) {
    //run sql
    $sql = "INSERT INTO complete (participants_id, test_id, cheated) VALUES ('$pid', '$tid', '$setCheated')";
    if (mysqli_query($sqlConnectStart, $sql)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
//not used at the moment table does not exist leaving it here for later.
function checkCompletedTest($email, $testID){
  global $debugLog, $sqlConnectStart;
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $usersEmail = $email;
  }
  if (is_int($testID)) {
    $tid = $testID;
  }
  if (isset($usersEmail) and isset($tid)) {
    $getUserID = "SELECT complete.test_id, participants.name, participants.surname FROM complete INNER JOIN participants ON participants.id = complete.participants_id WHERE participants.email = '$usersEmail' AND complete.test_id = $tid";
    $getUserIDResults = mysqli_query($sqlConnectStart, $getUserID);
    if (mysqli_num_rows($getUserIDResults) > 0) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
//complile test results from db
function compileTestResults($userID, $testID) {
  global $debugLog, $sqlConnectStart;
  if (is_int($userID)) {
    $pid = $userID;
  }
  if (is_int($testID)) {
    $tid = $testID;
  }
  if (isset($pid) and isset($tid)) {
    $countTotalAwnsers = 0;
    $countCorrectAwnsers = 0;
    $getMarksSQL = "SELECT id, answers_id, questions_id FROM marks WHERE test_id = '$tid' AND participants_id = '$pid'";
    $getMarksResults = mysqli_query($sqlConnectStart, $getMarksSQL);
    $countCorrectAwnsers = 0;
    while ($getMarks = mysqli_fetch_assoc($getMarksResults)) {
      $m_aid = $getMarks['answers_id'];
      $qid = $getMarks['questions_id'];
      $getQuestionsSQL = "SELECT questions FROM questions WHERE id = $qid";
      $getQuestionsResults = mysqli_query($sqlConnectStart, $getQuestionsSQL);
      $QuestionsResults = mysqli_fetch_assoc($getQuestionsResults);
      $getAnsewersSQL = "SELECT id, answers, is_correct FROM answers WHERE questions_id = $qid";
      $getAnsewersResults = mysqli_query($sqlConnectStart, $getAnsewersSQL);
      while ($ansewersResults = mysqli_fetch_assoc($getAnsewersResults)) {
        //compare marks and awnserts
        if ($m_aid ==  $ansewersResults['id']) {
          if ($ansewersResults['is_correct'] == 1) {
            $fullResults[$QuestionsResults['questions']][$ansewersResults['answers']] = "Correct";
            $countCorrectAwnsers = $countCorrectAwnsers + 1;
          } else {
            $fullResults[$QuestionsResults['questions']][$ansewersResults['answers']] = "Wrong";
          }
        }
      }
      $countTotalAwnsers = $countTotalAwnsers + 1;
    }
    $getQuestionsSQL = "SELECT COUNT(id) FROM `questions` WHERE test_id = '$tid'";
    $getQuestionsResults = mysqli_query($sqlConnectStart, $getQuestionsSQL);
    $QuestionsResults = mysqli_fetch_assoc($getQuestionsResults);
    $fullResults['Total'] = $QuestionsResults['COUNT(id)'];
    $fullResults['Currect'] = $countCorrectAwnsers;
    $fullResults['Wrong'] =  $QuestionsResults['COUNT(id)'] - $countCorrectAwnsers;
    $fullResults['Score'] = $countCorrectAwnsers /  $QuestionsResults['COUNT(id)'] * 100;
    return $fullResults;
  }
}
//return all tests from db into an array
function getTest() {
  global $debugLog, $sqlConnectStart;
  $getTestSQL = "SELECT id, name, description, time FROM test";
  //$getTestResults = mysqli_query($sqlConnectStart, $getTestSQL);
  if ($getTestResults = mysqli_query($sqlConnectStart, $getTestSQL)) {
    while ($getTest = mysqli_fetch_assoc($getTestResults)) {
      $testList[$getTest['id']]['id'] = $getTest['id'];
      $testList[$getTest['id']]['name'] = $getTest['name'];
      $testList[$getTest['id']]['description'] = $getTest['description'];
      $testList[$getTest['id']]['time'] = $getTest['time'];
    }
    if (isset($testList)) {
      return $testList;
    } else {
      $noTestData[0]['id'] = 0;
      $noTestData[0]['name'] = "No Test Yet!";
      $noTestData[0]['description'] = "Please come back later when a test is avalible";
      $noTestData[0]['time'] = 0;
      return $noTestData;
    }
  } else {
    $noTestData[0]['id'] = 0;
    $noTestData[0]['name'] = "No Test Yet!";
    $noTestData[0]['description'] = "Please come back later when a test is avalible";
    $noTestData[0]['time'] = 0;
    return $noTestData;
  }
}
//count the number of question for a spisific test
function countQuestions($testID) {
  global $debugLog, $sqlConnectStart;
  if (preg_match("/[0-9]{1}/", $testID)) {
    $tid = $testID;
  }
  if (isset($tid) and $tid != "0") {
    $getQuestionsSQL = "SELECT COUNT(id) FROM `questions` WHERE test_id = '$tid'";
    $getQuestionsResults = mysqli_query($sqlConnectStart, $getQuestionsSQL);
    $QuestionsResults = mysqli_fetch_assoc($getQuestionsResults);
    return $QuestionsResults['COUNT(id)'];
  }
}
//get currant partisipant from db for test
function getParticipantsFromTest($testID, $participantsEmail) {
  global $debugLog, $sqlConnectStart;
  if (is_int($testID)) {
    $tid = $testID;
  }
  if (filter_var($participantsEmail, FILTER_VALIDATE_EMAIL)) {
    $pemail = $participantsEmail;
  }

  if (isset($tid) and isset($pemail)) {
    $getParticipantsSQL = "SELECT id, date_created FROM participants WHERE email = '$pemail' AND test_id = '$tid' ORDER BY id DESC LIMIT 1";
    $getParticipantsResults = mysqli_query($sqlConnectStart, $getParticipantsSQL);

    if ($ParticipantsResultsData = mysqli_fetch_assoc($getParticipantsResults)) {
      $participantsDetails['id'] = $ParticipantsResultsData['id'];
      $participantsDetails['date'] = $ParticipantsResultsData['date_created'];
      return $participantsDetails;
    } else {
      return true;
    }
  } else {
    return false;
  }
}
//Get participant complted idea
function getParticipantsFromCompleted($testID, $participantsID) {
  global $debugLog, $sqlConnectStart;
  if (is_int($testID)) {
    $tid = $testID;
  }
  if (is_int($participantsID)) {
    $pid = $participantsID;
  }
  $getCompletedSQL = "SELECT id, date_created FROM complete WHERE participants_id = '$pid' AND test_id = '$tid' ORDER BY id DESC LIMIT 1";
  $getCompletedResults = mysqli_query($sqlConnectStart, $getCompletedSQL);
  $CompletedResults = mysqli_fetch_assoc($getCompletedResults);
  $completed['id'] = $CompletedResults['id'];
  $completed['date_created'] = $CompletedResults['date_created'];
  return $completed;
}
//Check if awnser is Correct
function checkAwnser($awnserID) {
  global $debugLog, $sqlConnectStart;
  if (is_int($awnserID)) {
    $aid = $awnserID;
  }
  //If validation passed continue
  if (isset($aid)) {
    $checkAwnserSQL = "SELECT id FROM `answers` WHERE is_correct = 1 AND id = $aid LIMIT 1";
    $checkAwnserResults = mysqli_query($sqlConnectStart, $checkAwnserSQL);
    if (mysqli_num_rows($checkAwnserResults) == 1) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
//Get last test ID
function getLastTestID() {
  global $debugLog, $sqlConnectStart;
  $testIDsql = "SELECT id FROM `test` ORDER BY id DESC LIMIT 1";
  $testIDResults = mysqli_query($sqlConnectStart, $testIDsql);
  $testIDFetch = mysqli_fetch_assoc($testIDResults);
  return $testIDFetch['id'];
}
//Get last question ID
function getLastQuestionID() {
  global $debugLog, $sqlConnectStart;
  $testIDsql = "SELECT id FROM `questions` ORDER BY id DESC LIMIT 1";
  $testIDResults = mysqli_query($sqlConnectStart, $testIDsql);
  $testIDFetch = mysqli_fetch_assoc($testIDResults);
  return $testIDFetch['id'];
}
//delete a test and all assoc objects
function deleteTest($testID) {
  global $debugLog, $sqlConnectStart;
  if (is_int($testID)) {
    $tid = $testID;
  }
  if (isset($tid)) {
    //remove marks
    $removeMarksSQL = "DELETE FROM marks WHERE test_id = $tid";
    if (mysqli_query($sqlConnectStart, $removeMarksSQL)) {
      $marksDelete = true;
    } else {
      $marksDelete = false;
    }
    //remove participants
    $removeParticipantsSQL = "DELETE FROM participants WHERE test_id = $tid";
    if (mysqli_query($sqlConnectStart, $removeParticipantsSQL)) {
      $marksDelete = true;
    } else {
      $marksDelete = false;
    }
    //remove compliete
    $removeCompleteSQL = "DELETE FROM complete WHERE test_id = $tid";
    if (mysqli_query($sqlConnectStart, $removeCompleteSQL)) {
      $marksDelete = true;
    } else {
      $marksDelete = false;
    }

    $getQuestionIdSQL = "SELECT id FROM questions WHERE test_id = $tid";
    $getQuestionsIdResults = mysqli_query($sqlConnectStart, $getQuestionIdSQL);
    while ($getQuestionsId = mysqli_fetch_assoc($getQuestionsIdResults)) {
      $qid = $getQuestionsId['id'];
      //remove awnserts
      $removeAwnsersSQL = "DELETE FROM answers WHERE questions_id = $qid";
      if (mysqli_query($sqlConnectStart, $removeAwnsersSQL)) {
        $awnsersDeleted = true;
      } else {
        $awnsersDeleted = false;
      }
    }
    //remove the questions
    $removeQuestionsSQL = "DELETE FROM questions WHERE test_id = $tid";
    if (mysqli_query($sqlConnectStart, $removeQuestionsSQL)) {
      $questionsDelete = true;
    } else {
      $questionsDelete = false;
    }

    $removeTestSQL = "DELETE FROM test WHERE id = $tid";
    if (mysqli_query($sqlConnectStart, $removeTestSQL)) {
      $testDeleted = true;
    } else {
      $testDeleted = false;
    }

    if (!isset($awnsersDeleted)) {
      //Run test delete again
      $removeTestSQL = "DELETE FROM test WHERE id = $tid";
      if (mysqli_query($sqlConnectStart, $removeTestSQL)) {
        $testDeleted = true;
      } else {
        $testDeleted = false;
      }
    }
  }
  if ($testDeleted == true) {
    return true;
  } else {
    return false;
  }
}
//get anwsers id based on question id's
function getAnwserID($questionID) {
  global $debugLog, $sqlConnectStart;
  if (is_int($questionID)) {
    $qid = $questionID;
  }
  if (isset($qid)) {
    $getAwnserIDSQL = "SELECT id answers FROM answers WHERE questions_id = $qid";
    $getAwnserIDResults = mysqli_query($getAwnserIDSQL);
    while ($getAwnserID = mysqli_fetch_assoc($getAwnserIDResults)) {
      $buildIDList[$getAwnserID['id']] = $getAwnserID['answers'];
    }
  }
  return $buildIDList;
}
//update Question
function updateQuestions($questionID, $data) {
  global $debugLog, $sqlConnectStart;
  if (is_int($questionID)) {
    $qid = $questionID;
  }
  if (isset($qid)) {
    $updateQuestionSQL = "UPDATE questions SET questions = '$data' WHERE id = $qid";
    $updateQuestionResults = mysqli_query($sqlConnectStart, $updateQuestionSQL);
    if ($updateQuestionResults) {
      return true;
    } else {
      return false;
    }
  }
}
//update Awnsers
function updateAwnsers($awnserID, $data) {
  global $debugLog, $sqlConnectStart;
  if (is_int($awnserID)) {
    $aid = $awnserID;
  }
  if (isset($aid)) {
    $updateAwnsersSQL = "UPDATE answers SET answers = '$data' WHERE id = $aid";
    $updateAwnsersResults = mysqli_query($sqlConnectStart, $updateAwnsersSQL);
    if ($updateAwnsersResults) {
      return true;
    } else {
      return false;
    }
  }
}
//Update is correct
function updateIsCorrect($awnserID, $isCorrect) {
  global $debugLog, $sqlConnectStart;
  if (is_int($awnserID)) {
    $aid = $awnserID;
  }

  if (is_int($isCorrect)) {
    $iC = $isCorrect;
  }

  if (isset($aid) and isset($iC)) {
    $updateAwnsersSQL = "UPDATE answers SET is_correct = $iC WHERE id = $aid";
    $updateAwnsersResults = mysqli_query($sqlConnectStart, $updateAwnsersSQL);

    if ($updateAwnsersResults) {
      return true;
    } else {
      return false;
    }
  }
}
//reset is correct
function resetIsCorrect($questionID) {
  global $debugLog, $sqlConnectStart;
  if (is_int($questionID)) {
    $qid = $questionID;
  }

  if (isset($questionID)) {
    $updateAwnsersSQL = "UPDATE answers SET is_correct = 0 WHERE questions_id = $qid";
    $updateAwnsersResults = mysqli_query($sqlConnectStart, $updateAwnsersSQL);
    if ($updateAwnsersResults) {
      return true;
    } else {
      return false;
    }
  }
}
//Delete question
function deleteQuestion($questionID) {
  global $debugLog, $sqlConnectStart;

  if (is_int($questionID)) {
    $qid = $questionID;
  }

  if (isset($qid)) {

  }
}
//Conplile Test Results
function complileAdminResultsByTest() {
  global $debugLog, $sqlConnectStart;
  //$resultsByTest = '';
  //Get test details from DB
  $getTestsSQL = "SELECT * FROM test";
  $getTestResults = mysqli_query($sqlConnectStart, $getTestsSQL);
  $countTests = 0;
  while ($getTest = mysqli_fetch_assoc($getTestResults)) {
    $countTests = $countTests + 1;
    $countQuestions = 0;
    $countCorrect = 0;
    $countParticipants = 0;
    $tid = $getTest['id'];
    $resultsByTest['count'] = $countTests;
    $resultsByTest['test'][$tid]['id'] = $getTest['id'];
    $resultsByTest['test'][$tid]['name'] = $getTest['name'];
    $resultsByTest['test'][$tid]['description'] = $getTest['description'];
    $getParticipantsSQL = "SELECT * from participants WHERE test_id = $tid";
    $getParticipantsResults = mysqli_query($sqlConnectStart, $getParticipantsSQL);
    //Start building the results into usable array that will be retund by function
    $adminResultsList = '';
    while ($getParticipants = mysqli_fetch_assoc($getParticipantsResults)) {
      $countParticipants = $countParticipants + 1;
      $resultsByTest['test'][$tid]['partispans']['count'] = $countParticipants;
      $pid = $getParticipants['id'];
      //Get Complitaion Date and Cheat Status
      $getCompletedSQL = "SELECT * FROM complete WHERE participants_id = $pid LIMIT 1";
      $getCompletedResults = mysqli_query($sqlConnectStart, $getCompletedSQL);
      $getCompleted = mysqli_fetch_assoc($getCompletedResults);
      //Build Array of results
      $resultsByTest['test'][$tid]['partispans'][$pid]['id'] = $getParticipants['id'];
      $resultsByTest['test'][$tid]['partispans'][$pid]['name'] = $getParticipants['name'];
      $resultsByTest['test'][$tid]['partispans'][$pid]['email'] = $getParticipants['email'];
      $resultsByTest['test'][$tid]['partispans'][$pid]['test_id'] = $getParticipants['test_id'];
      $resultsByTest['test'][$tid]['partispans'][$pid]['date_start'] = $getParticipants['date_created'];
      $resultsByTest['test'][$tid]['partispans'][$pid]['date_end'] = $getCompleted['date_created'];
      $startTime = $getParticipants['date_created'];
      $endTime = $getCompleted['date_created'];
      //Calculate time diffrence between start and end date with mysql function
      $getTimeDiffSQL = "SELECT TIMEDIFF('$endTime','$startTime') AS total";
      $getTimeDiffResults = mysqli_query($sqlConnectStart, $getTimeDiffSQL);
      $getTimeDiff = mysqli_fetch_assoc($getTimeDiffResults);
      $resultsByTest['test'][$tid]['partispans'][$pid]['time'] = $getTimeDiff['total'];
      $resultsByTest['test'][$tid]['partispans'][$pid]['cheated'] = $getCompleted['cheated'];
      //Get partispans marks
      $getMakrsSQL = "SELECT * FROM marks WHERE participants_id = $pid";
      $getMakrsResults = mysqli_query($sqlConnectStart, $getMakrsSQL);
      while ($getMakrs = mysqli_fetch_assoc($getMakrsResults)) {
        $countQuestions = $countQuestions + 1;
        $mid = $getMakrs['id'];
        $aid = $getMakrs['answers_id'];
        $qid = $getMakrs['questions_id'];
        //Get Questions and Awnserts
        $getQuestionsSQL = "SELECT * FROM questions WHERE id = $qid LIMIT 1";
        $getQuestionsResults = mysqli_query($sqlConnectStart, $getQuestionsSQL);
        $getQuestions = mysqli_fetch_assoc($getQuestionsResults);
        $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['id'] = $getQuestions['id'];
        $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['question'] = $getQuestions['questions'];
        //Get Awnsers for question from Marks
        $getAwnserSQL = "SELECT * FROM answers WHERE id = $aid LIMIT 1";
        $getAwnserResults = mysqli_query($sqlConnectStart, $getAwnserSQL);
        $getAwnser = mysqli_fetch_assoc($getAwnserResults);

        if ($getAwnser['is_correct'] == 1) {
          $countCorrect = $countCorrect + 1;
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['id'] = $getAwnser['id'];
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['answers'] = $getAwnser['answers'];
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['correct'] = true;
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['date_created'] = $getQuestions['date_created'];
        } else {
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['id'] = $getAwnser['id'];
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['answers'] = $getAwnser['answers'];
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['correct'] = false;
          $resultsByTest['test'][$tid]['partispans'][$pid]['questions'][$qid]['answers'][$aid]['date_created'] = $getQuestions['date_created'];
        }
        $resultsByTest['test'][$tid]['partispans'][$pid]['totalQuestions'] = $countQuestions;
        $resultsByTest['test'][$tid]['partispans'][$pid]['correctQuestions'] = $countCorrect;
        $resultsByTest['test'][$tid]['partispans'][$pid]['wrongQuestions'] = $countQuestions - $countCorrect;
        $resultsByTest['test'][$tid]['partispans'][$pid]['score'] = (int)($countCorrect / $countQuestions * 100);
      }
    }
  }
  return $resultsByTest;
}
//Get settings from DB
function getSettings() {
  global $debugLog, $sqlConnectStart;
  $getSettingsSQL = "SELECT name, value FROM `settings`";
  $querySettingsSQL = mysqli_query($sqlConnectStart, $getSettingsSQL);
  if (mysqli_num_rows($querySettingsSQL) > 0) {
    while ($settings = mysqli_fetch_assoc($querySettingsSQL)) {
      $settingsData[$settings['name']] =  $settings['value'];
    }
  }
  return $settingsData;
}
//Close DB
function closeDB() {
  global $debugLog, $sqlConnectStart;
  $sqlConnectEnd = mysqli_close($sqlConnectStart);
  if ($sqlConnectEnd) {
    $debugLog[] = "SQL Connection Ended";
  } else {
    $debugLog[] = "SQL Connection Unable to Ended";
  }
}
//END SQL Functions
?>
