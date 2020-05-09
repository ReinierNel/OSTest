<?php
if (!session_id()) {
    session_start();
  }

  include('sql.php');

echo userAuth('new', 'admin', 'admin');

?>