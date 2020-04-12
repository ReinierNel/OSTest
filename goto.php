<?php
//Handle GET
if (isset($_GET['name']) and !empty($_GET['name'])) {
  if (preg_match("/^[a-zA-Z-\040]+$/", $_GET['name'])) {
    $name = urldecode ($_GET['name']);
  }
}
if (isset($_GET['email']) and !empty($_GET['email'])) {
  if (filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    $email = urldecode ($_GET['email']);
  }
}
if (isset($_GET['tid']) and !empty($_GET['tid'])) {
  if (is_int((int)$_GET['tid'])) {
    $tid = urldecode ($_GET['tid']);
  }
}
//If all get handelers has passed genirate invisble form
if (isset($name) and isset($email) and isset($tid)) {
  echo '<form action="test.php" method="post">';
  echo '<input type="hidden" name="name" value="' . $name . '">';
  echo '<input type="hidden" name="email" value="' . $email . '">';
  echo '<input type="hidden" name="test" value="' . $tid . '">';
  echo '<button type="submit" id="goto">Click here if you dont get redirected automatically...</button>';
  echo '</form>';
}
?>
<script type="text/javascript">
  document.getElementById('goto').click();
</script>
