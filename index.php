<?php
  $page = isset($_GET['page']) ? $_GET['page'] : 'home';
  $phpScriptPath = $page . '.php';
  $htmlPath = $page . '.html';
  if (file_exists($phpScriptPath)) {
    include($phpScriptPath);
  } 
  elseif (file_exists($htmlPath)) {
    include($htmlPath);
  }
  else {
    echo "The page you are looking for does not exist.";
  }

?>