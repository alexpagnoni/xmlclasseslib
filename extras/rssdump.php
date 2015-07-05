<?php
// This example shows how the RSS_parser class can be used.

include_once("class_rss_parser.php");

$rss=new RSS_parser();
$rss->rss_parse("http://www.phpclasses.org/browse.html/latest/latest.xml");
$ch=$rss->get_channel_data();
$ch_im=$rss->get_channel_image_data();
$ch_ti=$rss->get_channel_textinput_data();
$items=$rss->get_items_data();
// Print the information here:
print("<b>Channel data:</b><br/>");
print_r($ch);
print("<br/>");
print("<b>Channel image data:</b><br/>");
print_r($ch_im);
print("<br/>");
print("<b>Channel textinput data:</b><br/>");
print_r($ch_ti);
print("<br/>");
print("<b>Items data:</b><br/>");
foreach($items as $item) {
  print("----<br/>");
  foreach($item as $key=>$val) {
     print("$key: $val<br/>");
  }  
  
}
print("<br/>");

?>