<html>
	<head>
		<title> search stock </title>
        <style type="text/css">
		.toright {margin-left:-50px}
		div.addborder {border-style: solid ;border-width:medium;width:500px}
		div.display1 { width:800px };
		.lft {text-align:left};
		.first1 {text-align:left}
		b#bigger {font-size:22px}
		.c1 = {align="right"}
        </style>
	</head>
	
	<body>
     
    <center>
    	<H1>Market Stock Search</h1> 
        <div class = "addborder">
		<form id="searchform" action="" method="post">
			Company Symbol:<input name="company" type=“text”>
            <INPUT type=submit name="submit" value="Search">
		</form>	
    <p class="toright">Example:GOOG,MSFT,YHOO,FB,AAPL,...etc</p>	
    </div>
    </center>
    <?php 
	
		if(isset($_POST["submit"]))
		{
			$company = $_POST["company"];
			if($company == null || $company =="")
			{
			echo "<script language=\"JavaScript\">\r\n"; 
			echo " alert(\"Please enter a company symbol\");\r\n"; 
			echo "</script>";
			}
			else
			{
				Header("Content-type:text/html;charset=utf-8"); 
					
			$xmlurl = urlencode("http://query.yahooapis.com/v1/public/yql?q=Select%20Name%2C%20Symbol%2C%20LastTradePriceOnly%2C%20Change%2C%20ChangeinPercent%2C%20PreviousClose%2C%20DaysLow%2C%20DaysHigh%2C%20Open%2C%20YearLow%2C%20YearHigh%2C%20Bid%2C%20Ask%2C%20AverageDailyVolume%2C%20OneyrTargetPrice%2C%20MarketCapitalization%2C%20Volume%2C%20Open%2C%20YearLow%20from%20yahoo.finance.quotes%20where%20symbol%3D%22".$company."%22&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys");
			
			//echo "<a href = $xmlurl>"."xmlurl"."</a>";
			
			
			$newsurl = urlencode("http://feeds.finance.yahoo.com/rss/2.0/headline?s=".$company."&region=US&lang=en-US");
			
			//echo "<a href = $newsurl>"."newsurl"."</a><br>";
			}  
			   	$xml = simplexml_load_file($xmlurl);
				 
                $xmlvalue = $xml->results->quote;
				
				$name = $xmlvalue->Name;
				
				$symbol = $xmlvalue->Symbol;
				
				function changeformat($input)
				{
					
					if($input == "" || $input == null)
					{
						return "";
					}
					
					$input = (double) $input;
					
					if($input<0)
					{
						$input = -$input;
					}
					
					
						$output = number_format($input,2);
					return $output;
				}
				
				function changeformat2($input)
				{
					if($input == "" || $input == null)
					{
						return "";
					}
					$input = (double) $input;
						$output = number_format($input,0);
					return $output;
				}
				function changeformat3($input)
				{
					if($input == "" || $input == null)
					{
						return "";
					}
					$input = (double) $input;
						$output = number_format($input,1);
					return $output;
				}
				
				function judgechange($input)
				{
					$input = (double) $input;
					if($input > 0)
					{
						return 1;
					}
					if($input < 0)
					{
						return 0;
					}
					if($input == 0)
					{
						return 2;
					}
				}
				
				$bid = changeformat($xmlvalue->Bid);
				$prec = changeformat($xmlvalue->PreviousClose);
				$dl = changeformat($xmlvalue->DaysLow);
				$dh = changeformat($xmlvalue->DaysHigh);
				$open = changeformat($xmlvalue->Open);
				$change = $xmlvalue->Change;
				$yl = changeformat($xmlvalue->YearLow);
				$yh = changeformat($xmlvalue->YearHigh);
				$vl = changeformat2($xmlvalue->Volume);
				$ask = changeformat($xmlvalue->Ask);
				$avl =changeformat2($xmlvalue->AverageDailyVolume);
				$mc = $xmlvalue->MarketCapitalization;
				$mc1 = changeformat3(substr($mc, 0, -1));
				$mc2 = substr($mc, -1);
				$oyt = changeformat($xmlvalue->OneyrTargetPrice);
				$lasttradeonly = changeformat($xmlvalue->LastTradePriceOnly);
				$updown = judgechange($xmlvalue->Change);
				$change_c = changeformat($xmlvalue->Change);
				$per = changeformat($xmlvalue->ChangeinPercent);
				
				
				
				if($change==null||$change =="")
				{
					echo "<center><h1>Stock Information Not Available</h1>";
				}
				else
				{
					
					echo "<center><div class=\"display1\">";
					
					echo "<center><h2>Search Results</h2></center>";	
					echo "<p style=\"text-align:left\"><table border=\"0\"><tr><td><b id=\"bigger\">".$name."(".$symbol.")</b></td> <td>$lasttradeonly</td>";
				
					if($updown == 1)
					{
						echo "<td><img src=\"up_g.gif\">";
						echo "<font color=green>$change_c($per%)</font></td>";
					}
					if($updown == 0)
					{
						echo "<td><img src=\"down_r.gif\">";
						echo "<font color=#FF0000>$change_c($per%)</font></td>";
					}
					if($updown == 2)
					{
						echo "<td>";
						echo "<font color=green>$change_c($per%)</font></td>";
					}
					echo "</tr></table>";
				
					echo "<hr noshade color=\"black\" size = \"5\">";
					echo "<p style=\"text-align:left\"><table border=\"0\"><tr><td  width=\"200\">Prev Close:</td><td width=\"90\">$prec</td>";
					if(($dl == null || $dl =="")&&($dh == null || $dh ==""))
					{
					echo "<td  width=\"250\">Day's Range:</td><td style=\"text-align:right\">$dl$dh</td></tr>";
					}
					else
					{
						echo "<td  width=\"250\">Day's Range:</td><td style=\"text-align:right\">$dl-$dh</td></tr>";
					}
					echo "<tr><td  width=\"200\">Open:</td><td width=\"90\">$open</td>";
					if(($yl == null || $yl =="")&&($yh == null || $yh ==""))
					{
					echo "<td  width=\"250\">Day's Range:</td><td style=\"text-align:right\">$yl$yh</td></tr>";
					}
					else
					{
						echo "<td  width=\"250\">52wk Range:</td><td style=\"text-align:right\">$yl-$yh</td></tr>";
					}
					
				
					echo "<tr><td  width=\"200\">Bid:</td><td width=\"90\">$bid</td>";
					echo "<td  width=\"250\">Volume:</td><td style=\"text-align:right\">$vl</td></tr>";
				
					echo "<tr><td  width=\"200\">Ask:</td><td width=\"90\">$ask</td>";
					echo "<td  width=\"250\">Avg Vol(3m):</td><td style=\"text-align:right\">$avl</td></tr>";
				
					echo "<tr><td  width=\"200\">1yr Target Est:</td><td width=\"90\">$oyt</td>";
					
					echo "<td  width=\"250\">Market Cap:</td><td style=\"text-align:right\">$mc1$mc2</td></tr>";
				
						echo "</table></p></center></div>";
				
 				
 				
 				echo "<div>";
 					
 					
 				
				$rss = simplexml_load_file($newsurl);
			
				//echo "".($rss->channel->title)."<br>";
				if(($rss->channel->title) != "Yahoo! Finance: RSS feed not found")
				{
						echo "<center><div class=\"display1\">";
				//echo $newsurl->channel->title;
				echo "<p style=\"text-align:left\"><table border=\"0\"><tr><td><b id=\"bigger\">News Headlines</b></td><tr></table><hr noshade color=\"black\" size = \"5\">";
				//echo "<table></table><hr>";
				//echo "<div style = \"text-align:left\"><b id=\"bigger\">News Headlines</b><hr></div>";
				foreach($rss->children() as $child)
  				{
  					//echo $child->getName() . ": " . $child . "<br />";
					echo "<ul style=\"text-align:left\">";
					foreach($child->children() as $kid)
					{
						//echo $kid->getName() . ": " . $kid . "<br />";
						
						if($kid->getName() == "item")
						{
							foreach($kid->children() as $grandkids)
							{
								
								//echo "<ul>".$grandkids->getName() . ": " . $grandkids . "</ul>";
								if( $grandkids->getName() == "link")
								{
									$link1 = $grandkids;
									//echo "<li><a href=$link>";
								}
								if($grandkids->getName() == "title")
								{
									$link2 = $grandkids;
									//echo "$link </a></li>";
								}
							}
							echo "<li> <a href = $link1>$link2</a></li>";
						}
						
					}
					echo "</ul>";
					echo "</div>";
					}
				}
				else
				{
					echo "<center><h2>Financial Company News Is Not Available</h2>";
				}
			}
		}
		
/*
<Bid>1200.50</Bid>
<Change>-12.9601</Change>
<DaysLow>1192.14</DaysLow>
<DaysHigh>1207.84</DaysHigh>
<YearLow>761.26</YearLow>
<YearHigh>1228.88</YearHigh>
<MarketCapitalization>404.2B</MarketCapitalization>
<LastTradePriceOnly>1202.6899</LastTradePriceOnly>
<Name>Google Inc.</Name>
<Open>1206.75</Open>
<PreviousClose>1215.65</PreviousClose>
<ChangeinPercent>-1.07%</ChangeinPercent>
<Symbol>GOOG</Symbol>
<OneyrTargetPrice>1318.13</OneyrTargetPrice>
<Volume>2108720</Volume>
<Ask>1202.00</Ask>
<AverageDailyVolume>2132610</AverageDailyVolume>
				 */
	?>
    
    
    
	</body>
	
</html>