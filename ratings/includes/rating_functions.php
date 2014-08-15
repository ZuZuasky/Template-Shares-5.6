<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_rating ($RatingID = 0, $Userid = 0, $RType = 1)
  {
    global $BASEURL;
    global $charset;
    global $lang;
    global $usergroups;
    global $CURUSER;
    if ($usergroups['canrate'] != 'yes')
    {
      return $lang->global['nopermission'];
    }

    if ($RType == 2)
    {
      $lang->details['ratedetails'] = $lang->userdetails['ratedetails'];
      $lang->details['newrating'] = $lang->userdetails['newrating'];
    }

    $TotalRatings = $TotalRows = 0;
    $RatingID = (int)$RatingID;
    $Userid = (int)$Userid;
    $RType = (int)$RType;
    $Query = sql_query ('' . 'SELECT userid, rating_num FROM ratings WHERE type = \'' . $RType . '\' AND rating_id = \'' . $RatingID . '\'');
    if (0 < mysql_num_rows ($Query))
    {
      $IsUserRated = false;
      while ($Ratings = mysql_fetch_assoc ($Query))
      {
        $TotalRatings += $Ratings['rating_num'];
        ++$TotalRows;
        if ($Userid == $Ratings['userid'])
        {
          $IsUserRated = true;
          continue;
        }
      }
    }

    $Average = (0 < $TotalRows ? round ($TotalRatings / $TotalRows, 1) : 0);
    $RatingContents = '	
	<div id="Rating_' . $RatingID . '">			 	  
		<div class="indicator">' . sprintf ($lang->details['ratedetails'], $Average, $TotalRows) . '</div>
		<script language="javascript" type="text/javascript">
			function TSClicked(element, info)
			{
				var indicator = element.down(".indicator");
				var pars = "type=' . $RType . '&ratingid=' . $RatingID . '&rated=";
				new Ajax.Request("' . $BASEURL . '/ratings/includes/rating_process.php",
				{
					parameters: pars+info.rated,
					method: "POST",
					contentType: "application/x-www-form-urlencoded",
					encoding: 	"' . $charset . '",
					onLoading: function()
					{
						indicator.update("<b>' . $lang->global['pleasewait'] . '</b>");
						new Effect.Highlight(indicator);
					},
					onComplete: function(response)
					{
						var restore = response.responseText;
						if (restore == "")
						{
							indicator.update("' . $lang->details['newrating'] . ' " + (info.rated).toFixed(1));
						}
						else
						{
							indicator.update(restore);
						}
						new Effect.Highlight(indicator);
						//window.setTimeout(function() { indicator.update(restore); new Effect.Highlight(indicator); }, 3000);			   
					},
					onFailure: function ()
					{
						alert(l_ajaxerror);
					}
				});
			}
			new TSBox("Rating_' . $RatingID . '", "' . $Average . '", { indicator: "' . sprintf ($lang->details['ratedetails'], $Average, $TotalRows) . '", locked: ' . ($IsUserRated ? 'true' : 'false') . ', total: ' . $TotalRows . ', onRate: TSClicked });
		</script>
	</div>
	';
    return $RatingContents;
  }

  function gettoprated ($limit, $table, $idfield, $namefield)
  {
    $result = '';
    $sql = 'SELECT COUNT(ratings.id) as rates,ratings.rating_id,' . $table . '.' . $namefield . ' as thenamefield,ROUND(AVG(ratings.rating_num),2) as rating 
			FROM ratings,' . $table . ' WHERE ' . $table . '.' . $idfield . ' = ratings.rating_id GROUP BY rating_id 
			ORDER BY rates DESC,rating DESC LIMIT ' . $limit . '';
    $sel = sql_query ($sql);
    $result .= '<ul class="topRatedList">' . '
';
    while ($data = @mysql_fetch_assoc ($sel))
    {
      $result .= '<li>' . $data['thenamefield'] . ' (' . $data['rating'] . ')</li>' . '
';
    }

    $result .= '</ul>' . '
';
    return $result;
  }

?>
