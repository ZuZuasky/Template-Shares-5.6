<?php
/////////////////////////////////////////////////
//New Torrents Infinite Carousel by Nightcrawler
///////////////////////////////////////////////
require_once('global.php');
gzip();
dbconn(true);
maxsysop();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Infinite Carousel</title>
<style type="text/css" media="screen">
<!--
body { font: 1em "Trebuchet MS", verdana, arial, sans-serif; font-size: 100%; }
input, textarea { font-family: Arial; font-size: 125%; padding: 7px; }
label { display: block; } 

a:link {color: transparent; text-decoration: underline; border:0; }
a:active {color: transparent; text-decoration: underline; border:0; }
a:visited {color: transparent; text-decoration: underline; border:0; }
a:hover {color: transparent; text-decoration: none; border:0; }

.infiniteCarousel {
 height:130px;
	/*margin:0 auto;*/
	width:830px;
	overflow:hidden;
}


.infiniteCarousel .wrapper {
  width: 90%; /* .infiniteCarousel width - (.wrapper margin-left + .wrapper margin-right) */
  overflow:hidden;
  min-height: 10em;
  margin: 0 40px;
  position: absolute;
  top: 0;
}

.infiniteCarousel ul a img {
  border: 3px solid #000;
  -moz-border-radius: 5px;
  -webkit-border-radius: 0px;
}

.infiniteCarousel .wrapper ul {
  width: 9999px;
  list-style-image:none;
  list-style-position:outside;
  list-style-type:none;
  margin:0;
  padding:0;
  position: absolute;
  top: 0;
}

.infiniteCarousel ul li {
  display:block;
  float:left;
  padding: 10px;
  height: 85px;
  width: 85px;
}

.infiniteCarousel ul li a img {
  display:block;
}

.infiniteCarousel .arrow {
  
  height: 36px;
  width: 37px;
  background: url(images/arrow.png) no-repeat 0 0; 
  text-indent: -999px;
  position: absolute;
  top: 37px;
  cursor: pointer;
}

.infiniteCarousel .forward {
  background-position: 0 0;
  right: 0;
 

}

.infiniteCarousel .back {
  background-position: 0 -72px;
  left: 0;
   
}

.infiniteCarousel .forward:hover {
  background-position: 0 -36px;
}

.infiniteCarousel .back:hover {
  background-position: 0 -108px;
}



-->
</style>

<script src="scripts/jquery.min.js"></script>
<script src="scripts/overlib.js"></script>

<script type="text/javascript">

(function () {
    $.fn.infiniteCarousel = function () {
        function repeat(str, n) {
            return new Array( n + 1 ).join(str);
        }
        
        return this.each(function () {
            // magic!
            var $wrapper = $('> div', this).css('overflow', 'hidden'),
                $slider = $wrapper.find('> ul').width(9999),
                $items = $slider.find('> li'),
                $single = $items.filter(':first')
                
                singleWidth = $single.outerWidth(),
                visible = Math.ceil($wrapper.innerWidth() / singleWidth),
                currentPage = 1,
                pages = Math.ceil($items.length / visible);
                
            /* TASKS */
            
            // 1. pad the pages with empty element if required
            if ($items.length % visible != 0) {
                // pad
                $slider.append(repeat('<li class="empty" />', visible - ($items.length % visible)));
                $items = $slider.find('> li');
            }
            
            // 2. create the carousel padding on left and right (cloned)
            $items.filter(':first').before($items.slice(-visible).clone().addClass('cloned'));
            $items.filter(':last').after($items.slice(0, visible).clone().addClass('cloned'));
            $items = $slider.find('> li');
            
            // 3. reset scroll
            $wrapper.scrollLeft(singleWidth * visible);
            
            // 4. paging function
            function gotoPage(page) {
                var dir = page < currentPage ? -1 : 1,
                    n = Math.abs(currentPage - page),
                    left = singleWidth * dir * visible * n;
                
                $wrapper.filter(':not(:animated)').animate({
                    scrollLeft : '+=' + left
                }, 500, function () {
                    // if page == last page - then reset position
                    if (page > pages) {
                        $wrapper.scrollLeft(singleWidth * visible);
                        page = 1;
                    } else if (page == 0) {
                        page = pages;
                        $wrapper.scrollLeft(singleWidth * visible * pages);
                    }
                    
                    currentPage = page;
                });
            }
            
            // 5. insert the back and forward link
            $wrapper.after('<a href="#" class="arrow back" border=0>&lt;</a><a href="#" class="arrow forward" border=0>&gt;</a>');
            
            // 6. bind the back and forward links
            $('a.back', this).click(function () {
                gotoPage(currentPage - 1);
                return false;
            });
            
            $('a.forward', this).click(function () {
                gotoPage(currentPage + 1);
                return false;
            });
            
            $(this).bind('goto', function (event, page) {
                gotoPage(page);
            });
            
            // THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL
            $(this).bind('next', function () {
                gotoPage(currentPage + 1);
            });
        });
    };
})(jQuery);

$(document).ready(function () {
    // THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL
    var autoscrolling = true;
    
    $('.infiniteCarousel').infiniteCarousel().mouseover(function () {
        autoscrolling = false;
    }).mouseout(function () {
        autoscrolling = true;
    });
    
    setInterval(function () {
        if (autoscrolling) {
            $('.infiniteCarousel').trigger('next');
        }
    }, 10000);
});

</script>

</head>
<body>
<?php

if($CURUSER)
{
    $query="SELECT id, name, t_image, seeders,leechers, category, size, offensive FROM torrents WHERE t_image <> '' AND visible='yes' ORDER BY added DESC limit 30";
    $result=mysql_query($query);
    $num = mysql_num_rows($result);
    {
   ?>
    
    <div class="infiniteCarousel">
      <div class="wrapper">
        <ul>
        <?php
            while($row = mysql_fetch_assoc($result))
      {
       $t_name  = substr(htmlspecialchars($row[name]), 0, 30)."...";
       $res = mysql_query("SELECT name FROM categories WHERE id=$row[category]");
       $arr = mysql_fetch_assoc($res);
       $cat = $arr["name"];
       $name=$row["name"];
  ?>

          <li> <a href="details.php?id=<?php echo $row["id"]; ?>" target="_top" onmouseover=" return overlib('<table width=150 border=1><tr><td class=tablea><center><?=$name?><br><?=$cat?><br><font color=green><?=$row[seeders]?></font><font color=black> Seed\'s /</font> <font color=red><?=$row[leechers]?></font><font color=black> Leech\'s</font></center></td></tr></table>',BORDER, 2, CAPTIONSIZE, '0.5ems', TEXTFONT, 'Times Roman' ,CAPCOLOR, '#FFFF99',FGCOLOR, '#ADCBE7', VAUTO); " onmouseout="return nd();"><img src="<?php echo$row["t_image"];?>" height="75" width="75"></a></li>
          <?php
     }
  ?>
        </ul>        
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
