<?php
function h($s) {
  return htmlspecialchars($s, ENT_QUOTES);
}

function makeLink($value) {
  return preg_replace('/(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)/',
  '<a href="\\1\\2">\\1\\2</a>', $value);
}
?>
