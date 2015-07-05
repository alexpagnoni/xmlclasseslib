<?
session_start();
set_magic_quotes_runtime (0);

$example=Array();
$example["query"]=Array(
'<bib>
 {
  for $b in xmlmem($bib)/bib/book
  where $b/publisher = "Addison-Wesley" and $b/@year > 1991
  return
    <book year="{ $b/@year }">
     { $b/title }
    </book>
 }
 </bib> ',
 '<results>
  {
    for $b in xmlmem($bib)/bib/book,$t in $b/title,$a in $b/author
    return
        <result>
            { $t }
            { $a }
        </result>
  }
</results>',
'<results>
{
    for $b in xmlmem($bib)/bib/book
    return
        <result>
            { $b/title }
            { $b/author  }
        </result>
}
</results> ',
'<results>
  {
    for $a in distinct-values(xmlmem($bib)//author)
    return
        <result>
            {$a }
            {
                for $b in xmlmem($bib)/bib/book
                for $a2 in $b/author
                where $a2=$a
                return {$b/title}
            }
        </result>
  }
</results>',
'<books-with-prices>
  {
    for $b in xmlmem($bib)//book,
        $a in xmlmem($reviews)//entry
    where $b/title = $a/title
    return
        <book-with-prices>
            { $b/title }
            <price-amazon>{ $a/price/text() }</price-amazon>
            <price-bn>{ $b/price/text() }</price-bn>
        </book-with-prices>
  }
</books-with-prices>',
'<bib>
  {
    for $b in xmlmem($bib)//book
    where count($b/author) > 2
    return
        <book>
            { $b/title }
            {
                for $a in $b/author[position()<=2]  
                return {$a}
            }
            <et-al />
            </book>
  }

  {
    for $b in xmlmem($bib)//book
    where count($b/author) <= 2
    return
        <book>
            { $b/title }
            {
                for $a in $b/author[position()<=2]  
                return {$a}
            }
            </book>
  }

</bib>',
'',
'',
'<results>
{
    for $t in xmlmem($bib)//title[contains(./text(),"XML")]
    return {$t}
  }
</results> ',
'',
'<bib>
{
        for $b in xmlmem($bib)//book[author]
        return
            <book>
                { $b/title }
                { $b/author }
            </book>
}
{
        for $b in xmlmem($bib)//book[editor]
        return
          <reference>
            { $b/title }
            {$b/editor/affiliation}
          </reference>
}
</bib> ','');
$example["text"]=Array('List books published by Addison-Wesley after 1991, including their year and title.',
'Create a flat list of all the title-author pairs, with each pair enclosed in a "result" element.',
'For each book in the bibliography, list the title and authors, grouped inside a "result" element.',
'For each author in the bibliography, list the author\'s name and the titles of all books by that author, grouped inside a "result" element.',
'For each book found at both bn.com and amazon.com, list the title of the book and its price from each source.',
'For each book that has at least one author, list the title and first two authors, and an empty "et-al" element if the book has additional authors.',
'List the titles and years of all books published by Addison-Wesley after 1991, in alphabetic order.',
'Find books in which the name of some element ends with the string "or" and the same element contains the string "Suciu" somewhere in its content. For each such book, return the title and the qualifying element.',
'In the xmlmem "bib.xml", find all titles that contain the word "XML", regardless of the level of nesting.',
'In the xmlmem "prices.xml", find the minimum price for each book, in the form of a "minprice" element with the book title as its title attribute.',
'For each book with an author, return the book with its title and authors. For each book with an editor, return a reference with the book title and the editor\'s affiliation. ',
'Find pairs of books that have different titles but the same set of authors (possibly in a different order).');


if(!isset($_SESSION["bib"])) {
  $bib='<bib>
    <book year="1994">
        <title>TCP/IP Illustrated</title>
        <author><last>Stevens</last><first>W.</first></author>
        <publisher>Addison-Wesley</publisher>
        <price> 65.95</price>
    </book>
 
    <book year="1992">
        <title>Advanced XML Programming in the Unix environment</title>
        <author><last>Stevens</last><first>W.</first></author>
        <publisher>Addison-Wesley</publisher>
        <price>65.95</price>
    </book>
 
    <book year="2000">
        <title>Data on the Web</title>
        <author><last>Abiteboul</last><first>Serge</first></author>
        <author><last>Buneman</last><first>Peter</first></author>
        <author><last>Suciu</last><first>Dan</first></author>
        <publisher>Morgan Kaufmann Publishers</publisher>
        <price> 39.95</price>
    </book>
 
    <book year="1999">
        <title>The Economics of Technology and Content for Digital TV</title>
        <editor>
               <last>Gerbarg</last><first>Darcy</first>
                <affiliation>CITI</affiliation>
        </editor>
            <publisher>Kluwer Academic Publishers</publisher>
        <price>129.95</price>
    </book>
</bib>';
session_register("bib");
}

if(!isset($_SESSION["reviews"])) {
 $reviews='<reviews>
    <entry>
        <title>Data on the Web</title>
        <price>34.95</price>
        <review>
               A very good discussion of semi-structured database
               systems and XML.
        </review>
    </entry>
    <entry>
        <title>Advanced Programming in the Unix environment</title>
        <price>65.95</price>
        <review>
               A clear and detailed discussion of UNIX programming.
        </review>
    </entry>
    <entry>
        <title>TCP/IP Illustrated</title>
        <price>65.95</price>
        <review>
               One of the best books on TCP/IP.
        </review>
    </entry>
</reviews>';
session_register("reviews");
}

if(isset($_REQUEST["set"])) {
  $varname=$_REQUEST["varname"];
  // We process an update here
  ${$varname}=stripslashes($value);
  session_register("$varname"); 
}

if(isset($_REQUEST["recover"])) {
  session_unregister("bib");
  session_unregister("reviews"); 
  unset($bib);
  unset($reviews);
}
?>

<html>
<head>
  <title>Xquery Lite demo(PHP)</title>
  <link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
  <div align="center">
  <table border="0" width="80%">
  <tr>
    <td bgcolor="#aaaaee" class="textblbl" align="center">Xquery Lite demo</td>
  </tr>
  </table>
  <br />
  <table border="0" width="80%">
  <tr>
    <td bgcolor="#eeeeff" class="text">This demo explains how to solve the W3C "XMP" use cases using <a href="http://phpxmlclasses.sourceforge.net/xquery_lite.html">Xquery Lite 1.0.</a>
      The PHP implementation of Xquery Lite is used to show how the queries are evaluated and you can test your own queries too.
    </td>
  </tr>
  </table>
  <br />
  <table border="0" width="80%">
  <tr>
    <td bgcolor="#eeeeff" class="text">This is a list of the XML xmlmems used in the demo, xmlmems can be
      edited (under your own risk). If you mess things up click here to <a href="?recover=1">Recover the original examples</a>
      <ul>
        <li><a href="?edit=bib">bib.xml</a>: an xml xmlmem listing books
        <li><a href="?edit=reviews">reviews.xml</a>: an xml xmlmem with book reviews
      </ul>
    </td>
  </tr>
  </table>
  
  
  <?if(isset($edit)) {
      ?>
      <form method="post" action="<?=$_SERVER["PHP_SELF"]?>">
      <?
        print("<textarea name='value' rows='20' cols='80'>${$edit}</textarea><br/>");
      ?>
        <input type="hidden" name="varname" value="<?=$edit?>" />
        <input type="submit" name="set" value="update" />
        <input type="submit" name="cancel" value="cancel" />  
      </form>
      <?
    }
  ?>
  <? 
    if(isset($_REQUEST["execute"])) {
      $query=stripslashes($_REQUEST["query"]);
      include_once("class_xquery_lite.php");
      $xq=new XqueryLite();
      $result=$xq->evaluate_xqueryl($query);    
    }
  ?>
  <?
    if(isset($_REQUEST["examplenum"])) {
       $query=$example["query"][$_REQUEST["examplenum"]];
       $text=$example["text"][$_REQUEST["examplenum"]];
    }
  ?>
  <br/>
  <table border="0" width="80%">
  <tr>
    <td bgcolor="#eeeeff" class="text">This is a list of the queries that are prepared to be run over the sample
      xmlmems, you can try your own queries if you want <br/>
      <a href="?examplenum=0">Use-case 1</a>,&nbsp;<a href="?examplenum=1">Use-case 2</a>,&nbsp;<a href="?examplenum=2">Use-case 3</a>,&nbsp;<a href="?examplenum=3">Use-case 4</a>,&nbsp;
      <a href="?examplenum=4">Use-case 5</a>,&nbsp;<a href="?examplenum=5">Use-case 6</a>,&nbsp;<a href="?examplenum=6">Use-case 7</a>,&nbsp;<a href="?examplenum=7">Use-case 8</a>,&nbsp;
      <a href="?examplenum=8">Use-case 9</a>,&nbsp;<a href="?examplenum=9">Use-case 10</a>,&nbsp;<a href="?examplenum=10">Use-case 11</a>,&nbsp;<a href="?examplenum=11">Use-case 12</a>,&nbsp;
    </td>
  </tr>
  </table>
  <br/>
  <?if(isset($text)) {
     ?>
       <table border="0" bgcolor="ddddff" width="80%">
       <tr>
       <td align="center" valign="top" class="text" width="50%">
       <? print("<p>Query: <i>$text</i></p>");    ?>
       </td>
       </tr>
       </table>
     <?
    }
  ?>
  <form method="post" action="<?=$_SERVER["PHP_SELF"]?>">
    <table border="0" width="80%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" bgcolor="#ccccff" class="textbl">Query</td>
      <td align="center" bgcolor="#ddddff" class="textbl">Result</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#ccccff" class="text">
        <textarea name="query" rows="20" cols="40"><?if(isset($query)) {print($query);}?></textarea></td>
      <td align="center" bgcolor="#ddddff" class="text"><textarea name="result" rows="20" cols="40"><?if(isset($result)){print($result);}?></textarea></td>
      </tr>
    <tr>
      <td align="center" bgcolor="#aaaaff" class="text" colspan="2"><input type="submit" name="execute" value="execute" /></td>
    </tr>
    </table>
    <input type="hidden" name="text" value="<?=$text?>" />
  </form>    
</body>
</html>