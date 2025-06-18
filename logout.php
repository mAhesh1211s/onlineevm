<?php
session_start();
session_destroy();
header("Location: ../index.html"); // Or adjust path if needed
exit();
?>
