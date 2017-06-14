<?php
$comma = "";
foreach($headers as $header) {
    print "$comma\"$header\"";
    $comma = ',';
}
