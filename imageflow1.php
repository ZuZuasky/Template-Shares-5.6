<?php
require_once('global.php');
gzip();
dbconn(true);
maxsysop();


 ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
     <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
       <title>chargement</title>
        </head>
       <body>
      <link rel="stylesheet" href="imageflow/screen.css" type="text/css" media="screen" />
     <script language="JavaScript" type="text/javascript" src="imageflow/imageflow.js"></script>
    <div align="center">
   <?php

 if($CURUSER)
{
    $query="SELECT id, title, image FROM wcddl_downloads WHERE image <> '' ORDER BY dat DESC limit 30";
    $result=mysql_query($query);
    $num = mysql_num_rows($result);
    {
   ?>
      <div id="imageflow"> 
        <div id="loading">
          <b>chargement</b><br/>
          <img src="imageflow/loading.gif" width="208" height="13" alt="loading" /> 
        </div>
      <div id="images">
     <?php
     
    while($row = mysql_fetch_assoc($result))
      {
       $title  = substr(htmlspecialchars($row[title]), 0, 30)."...";

       
  ?>
        <img src="<?=$row["image"]?>"width="85" height="115"  longdesc="detailddl.php?id=<?=$row["id"]?>" alt="<br><br><br><font color=green><br><?=$title?></font><br><br />" />
  <?php
     }
  ?>
      </div>
       <div id="captions"></div>
        <div id="scrollbar">
         <div id="slider"></div>
        </div>
       </div>
      </div>
     <?php
     }
    }
   ?>
    </body>
   </html>
  <?php

?>