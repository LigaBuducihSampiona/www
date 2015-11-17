<?php

function format_date($date) {
  return date("d.M Y, H:i", strtotime($date)) . "<sup>h</sup>";
}

?>