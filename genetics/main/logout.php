<?php 

session_name('gedSession'); 
session_start(); 


global $HTTP_SESSION_VARS; 
$nrOfVars = 0; 
foreach ($HTTP_SESSION_VARS as $key => $value) 
          { 
              session_unregister($key) ; 
              $nrOfVars++; 
          } 
if ($nrOfVars > 0) 
          { 
             // there were some session variables set and we chose to signout, so do it 
             session_destroy(); # force the session to end 
          } 

//send to main menu
header("Location: index.php");

?>
