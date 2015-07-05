<?

// PLEASE CHANGE THIS TO POINT TO YOUR XINDICE SERVER
$server="localhost";
$port=4080;
$base="/db";

include_once("class_xindice.php");

print("<table width='600' bgcolor='#9999cc' border='1' bordercolor='#000000'><tr><td><h3>Testing Xindice</h3></td></tr></table><br />");

// Create the Xindice access object
$xi=new Xindice("$server",$port);
if(!$xi) {
die("Cannot create Xindice object, check servername and port<br />");
}
$xi->setXmlRpcDebug(0);

// Create a collection "pepe" under db 
$xi->createCollection($base,"pepe");


// Insert a sample document there
$a_doc='
<cosa>
  <person>
    <name>Foo</name>
    <age>10</age>
  </person>
  <person>
    <name>Goo</name>
    <age>83</age>
  </person>
</cosa>';
$xi->insertDocument("$base/pepe","cosa",$a_doc);

// List collections under base, should be an Array with "system" and "pepe"
$cols=$xi->listCollections($base);
print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td colspan='2' bgcolor='#9999cc'>Collections under $base:</td></tr>");
foreach($cols as $col) {
  print("<tr><td>Collection</td><td>$col</td></tr>");
}
print("</table><br/>");

// List number of documents under base/pepe should be 1 (the inserted doc)
$tot=$xi->getDocumentCount($base."/pepe");
  print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td bgcolor='#9999cc'>Documents in $base/pepe: $tot</td></tr>");
if($tot==1) {
  print("<tr><td>Test passed ok</td></tr></table>");
}
print("<br/>");

// List the documents under base/pepe should be "cosa"
$docs=$xi->listDocuments($base."/pepe");
print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td colspan='2' bgcolor='#9999cc'>Documents in $base/pepe: $tot</td></tr>");
foreach($docs as $doc) {
  print("<tr><td>Document</td><td>$doc</td></tr></table>");
}
print("<br/>");

// Retrieve the "cosa" document
$cosa=$xi->getDocument($base."/pepe","cosa");
print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td bgcolor='#9999cc'>Document  cosa:</td></tr>");
print("<tr><td><textarea rows='11' cols='40'>$cosa</textarea></td></tr></table>");
print("<br />");

// Remove the document
$xi->removeDocument($base."/pepe","cosa");

// List number of documents under base/pepe should be 0
$tot=$xi->getDocumentCount($base."/pepe");
print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td bgcolor='#9999cc'>Documents in $base/pepe after removal: $tot</td></tr>");
if($tot==0) {
  print("<tr><td>Test passed ok</td></tr></table>");
}
print("<br />");

// Re-insert the document
$xi->insertDocument($base."/pepe","cosa",$cosa);

// Make an Xpath query on the collection
$query=$xi->queryCollection($base."/pepe","XPath","//name");
print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td bgcolor='#9999cc'>Query result (queryCollection):</td></tr>");
print("<tr><td><textarea rows='11' cols='40'>$query</textarea></td></tr></table>");
print("<br />");


// And the document
$query=$xi->queryDocument($base."/pepe","XPath","//name","cosa");
print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td bgcolor='#9999cc'>Query result (queryDocument):</td></tr>");
print("<tr><td><textarea rows='11' cols='40'>$query</textarea></td></tr></table>");
print("<br />");


// An Xupdate modification
$modification='<?xml version="1.0"?>  <xupdate:modifications version="1.0" xmlns:xupdate="http://www.xmldb.org/xupdate">    <xupdate:insert-after select="/cosa/person[1]" > <xupdate:element name="person"><name>Pirincho</name><age>2</age></xupdate:element></xupdate:insert-after></xupdate:modifications>
';
$query=$xi->queryDocument($base."/pepe","XUpdate","$modification","cosa");

// Retrieve the updated document
$cosa=$xi->getDocument("/db/pepe","cosa");
print("<table width='600' bgcolor='#ccccff' border='1' bordercolor='#000000'><tr><td bgcolor='#9999cc'>Updated document:</td></tr>");
print("<tr><td><textarea rows='11' cols='40'>$cosa</textarea></td></tr></table>");
print("<br />");


// Drop the test collection
//$xi->dropCollection($base."/pepe");
?>
