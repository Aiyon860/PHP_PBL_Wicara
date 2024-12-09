<?php
  session_start();
  $_SESSION["id_user"] = null;
  $_SESSION["password"] = null;
  header("Location: ../../../index.php");