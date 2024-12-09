<?php 
  function censor_name($username, $is_anonymous) {
    if ($is_anonymous) {
      $censored_part = str_repeat('*', strlen($username) - 1);
      return $username[0] . $censored_part;
    } else {
      return $username; 
    }
  }