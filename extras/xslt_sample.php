<?
include_once("class_xslt.php");
$xslt=new Xslt();
$xslt->setXml("applications.xml");
$xslt->setXsl("tr1.xsl");
if($xslt->transform()) {
   $ret=$xslt->getOutput();
   echo $ret;
} else {
   print("Error:".$xslt->getError());
}
?>
