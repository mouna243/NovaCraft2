
<?php
session_start();
//katkhwi session
session_unset();

//katsali session niha2iyan
session_destroy();
header("Location:login");

