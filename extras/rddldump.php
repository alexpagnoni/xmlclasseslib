<?php

include_once("class_rddl.php");

$rddl=new RDDL_parser();
$rddl->rddl_parse("http://www.rddl.org/");
$resources=$rddl->get_resources();
print("<b>Resources:</b></br/>");
foreach($resources as $r) {
  foreach($r as $key => $val) {
    print("$key: $val<br/>"); 
  } 
  print("-------<br/>");
}

?>
