<?php
$a = array();

$a[] = "tes";
$a[] = "tas";

echo $a[1];

unset($a);
$a = array();
echo count($a);

?>