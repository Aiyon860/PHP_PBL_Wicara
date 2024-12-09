<?php
  function get_time_ago($datetime) {
    $timestamp = strtotime($datetime);
    $current_time = time();
    $diff = $current_time - $timestamp;
    
    $periods = array(
      31536000 => 'tahun',
      2592000 => 'bulan',
      604800 => 'minggu',
      86400 => 'hari',
      3600 => 'jam',
      60 => 'menit',
      1 => 'detik'
    );
    
    foreach ($periods as $seconds => $label) {
      $interval = floor($diff / $seconds);
      if ($interval > 0) {                    
        return $interval . ' ' . $label . ' yang lalu';
      }
    }
    
    return "Baru saja";
  }