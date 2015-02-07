<?php
	$ageValues = array();
	$sqlArgs = array();
	$dir = 'sqlite:../../shows.db';
    $dbh  = new PDO($dir) or die("cannot open the database");
	$query =  "SELECT DAY,VENUE,BANDS,PRICE,AGE,PIT,NO_INOUT,DRINK_TICKETS,RECOMMENDED,TIME,SOURCE_STRING from show_list WHERE SHORT_URL = :id";
	$showId = $_GET['id'];
		
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':id',$showId,PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetchAll();			
	
	foreach($result as $sqlArgs){
		if(strlen($sqlArgs[1]) > strlen($mapString)){
			$sqlArgs[1] = str_replace('S.F.','San Francisco',$sqlArgs[1]);
			$venueTitle = substr($sqlArgs[1],0,strpos($sqlArgs[1],','));
			$venueInfo = substr($sqlArgs[1],strpos($sqlArgs[1],',')+1,(strlen($sqlArgs[1])-strpos($sqlArgs[1],',')));

			$mapString = strtolower($sqlArgs[1]);
			$mapString = preg_replace("/[^a-z0-9_\s-]/", "", $mapString);
			$mapString = preg_replace("/[\s-]+/", " ", $mapString);
			$mapString = preg_replace("/[\s_]/", "+", $mapString);
		}
	}			
		
	$pageBody = '<div id="mapContainer">
					<iframe
						width="960"
						height="300"
						frameborder="0" style="border:0;margin: 0px auto;"
						src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA91BKt_0yqc1NOq_IIORyY1ydbjsuMIkI
						&q='.$mapString.'
						&attribution_source=Google+Maps+Embed+API
						&attribution_ios_deep_link_id=comgooglemaps://?daddr='.$mapString.'">
					</iframe>
				 </div>';
	
	
	foreach ($result as $row)
    {
		$venueLink = current(explode(",", $row[1]));
		$bandArray = explode('|@|',$row[2]);
		$bandNum = count($bandArray);
		$day = date_create($row[0]);
		$sourceString = $row[10];
		$showAttributes = array();
		if($row[9]!=''){
			if(strpos($row[9],'/') == true){
				$showTimes = explode('/',$row[9]);
				array_push($showAttributes,'Doors at '.$showTimes[0].' • Show at '.$showTimes[1]);
			}
			else array_push($showAttributes,$row[9]);
		}
		if($row[4]!=''){
			if($row[4] == 'All Ages'){array_push($showAttributes,'All ages');}
			elseif($row[4] == '21+'){array_push($showAttributes,'21 and up');}
			elseif($row[4] == '18+'){array_push($showAttributes,'18 and up');}
			elseif($row[4] == '16+'){array_push($showAttributes,'16 and up');}
			else{array_push($showAttributes,'No age data');}
		}
		if($row[3]!=''){
			if($row[3] == 'free'){array_push($showAttributes,'Free!');}
			else if(strpos($row[3],'/') == true){
				$prices = explode('/',$row[3]);
				array_push($showAttributes,$prices[0].' pre-sale • '.$prices[1].' at the door');
			}					
			else{array_push($showAttributes,$row[3]);}
			
		
		}
		if($row[5]==1){array_push($showAttributes,'Mosh Pit');}
		if($row[6]==1){array_push($showAttributes,'No in/outs');}
		if($row[7]==1){array_push($showAttributes,'Under 21 must buy drink tix');}
		
		$showString = '';
		
		for($i=0;$i<count($showAttributes);$i++){
			$showString.=$showAttributes[$i];
			if($i != count($showAttributes)-1){
				$showString.=' • ';
			}
		}
		
		//Sets previous years to display the year
		if(date_format($day,'Y') < date('Y')){
			$dayString = date_format($day,'Y');
			$dayClass = 'yearAsDay';
		}
		else{
			$dayString = strtoupper(date_format($day,'l'));
			$dayClass = strtolower(date_format($day,'l'));
		}
		
		switch($bandNum){
			case 1:
				if(strlen($bandArray[0]) >= 20){
					$longOrNot = '-long';
				}
				else $longOrNot = '';
				$pageBody.='<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="onlyBand'.$longOrNot.'">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a></div>
							</div>
						</div>';
						break;
			case 2:
				if(strlen($bandArray[0])>=17){
					$col1length='-long';
				}
				elseif(strlen($bandArray[0])<=9){
					$col1length='-short';
				}
				else $col1length='';
				
				if(strlen($bandArray[1])>=17){
					$col2length='-long';
				}
				elseif(strlen($bandArray[1])<=9){
					$col2length='-short';
				}
				else $col2length='';
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands2Column oneBand'.$col1length.'">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a></div>
							</div>
							<div class="bands2Column oneBand'.$col2length.' padded">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a></div>
							</div>
						</div>';	
						break;
			case 3:
				if(strlen($bandArray[0])>=17){
					$col1length='-long';
				}
				else if(strlen($bandArray[0])<=9){
					$col1length='-short';
				}
				else $col1length='';
				
				if(strlen($bandArray[1])>=20 || strlen($bandArray[2])>=20){
					$col2length='-long';
				}
				else $col2length='';
				
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands2Column oneBand'.$col1length.'">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a></div>
							</div>
							<div class="bands2Column twoBands'.$col2length.' padded">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
							</div>
						</div>';	
						break;
			case 4:
				if(strlen($bandArray[0])>=20 || strlen($bandArray[1]) >=20){
					$col1length='-long';
				}
				else $col1length='';
				if(strlen($bandArray[2])>=20 || strlen($bandArray[3]) >=20){
					$col2length='-long';
				}
				else $col2length='';
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands2Column twoBands'.$col1length.'">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a></div>
							</div>
							<div class="bands2Column twoBands'.$col2length.' padded">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a></div>
							</div>
						</div>';
						break;
			case 5:
				if(strlen($bandArray[0])>=17 || strlen($bandArray[1]) >=17){
					$col1length='-long';
				}
				else $col1length='';
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands2Column twoBands'.$col1length.'">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a></div>
							</div>
							<div class="bands2Column threeBands padded">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a></div>
							</div>
						</div>';
						break;
			case 6:
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands2Column threeBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
							</div>
							<div class="bands2Column threeBands padded">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a></div>
							</div>
						</div>';
						break;
			case 7:
				if(strlen($bandArray[0])>=20 || strlen($bandArray[1]) >=20 || strlen($bandArray[2]) >=20){
					$col1length='-long';
				}
				else $col1length='';
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands2Column threeBands'.$col1length.'">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
							</div>
							<div class="bands2Column fourBands">
								<div class="bands fourBands padded"><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a></div>
							</div>
						</div>';
						break;
			case 8:
				if(strlen($bandArray[0])>=20 || strlen($bandArray[1]) >=20 || strlen($bandArray[2]) >=20 || strlen($bandArray[3]) >=20){
					$col1length='-long';
				}
				else $col1length='';
				if(strlen($bandArray[4])>=20 || strlen($bandArray[5]) >=20 || strlen($bandArray[6]) >=20 || strlen($bandArray[7]) >=20){
					$col2length='-long';
				}
				else $col2length = '';
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands2Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a></div>
							</div>
							<div class="bands2Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a></div>
							</div>
						</div>';
						break;
			case 9:
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands3Column threeBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band=">'.urlencode($bandArray[1]).'>'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band=">'.urlencode($bandArray[2]).'>'.$bandArray[2].'</a></div>
							</div>
							<div class="bands3Column threeBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="./?band=">'.urlencode($bandArray[4]).'>'.$bandArray[4].'</a><br /><a class="bandLink" href="./?band=">'.urlencode($bandArray[5]).'>'.$bandArray[5].'</a></div>
							</div>
							<div class="bands3Column threeBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="./?band=">'.urlencode($bandArray[7]).'>'.$bandArray[7].'</a><br /><a class="bandLink" href="./?band=">'.urlencode($bandArray[8]).'>'.$bandArray[8].'</a></div>
							</div>
						</div>';
						break;
			case 10:
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands3Column threeBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
							</div>
							<div class="bands3Column threeBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a></div>
							</div>
							<div class="bands3Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[8]).'">'.$bandArray[8].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[9]).'">'.$bandArray[9].'</a></div>
							</div>
						</div>';
						break;
			case 11:
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="showHeader">'.ucfirst($row[1]).'<font class="attributes">'.$showString.'</font></div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands3Column threeBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
							</div>
							<div class="bands3Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a></div>
							</div>
							<div class="bands3Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[8]).'">'.$bandArray[8].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[9]).'">'.$bandArray[9].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[10]).'">'.$bandArray[10].'</a></div>
							</div>
						</div>';
						break;
			case 12:
				$pageBody.='	<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
							<div class="bands3Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a></div>
							</div>
							<div class="bands3Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a></div>
							</div>
							<div class="bands3Column fourBands">
								<div class="bands"><a class="bandLink" href="./?band='.urlencode($bandArray[8]).'">'.$bandArray[8].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[9]).'">'.$bandArray[9].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[10]).'">'.$bandArray[10].'</a><br /><a class="bandLink" href="./?band='.urlencode($bandArray[11]).'">'.$bandArray[11].'</a></div>
							</div>
						</div>';
						break;
			default:
				foreach($bandArray as $band){
					$longBandList.='<a class="bandLink" href="./?band='.urlencode($band).'">'.$band.'</a> ';
				}				
				$pageBody.='<div class="singleContainer">
							<div class="blackbox">
								<div class="dateBox">
									<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
									<div class="'.$dayClass.'">'.$dayString.'</div>
								</div>
							</div>
							<div class="showHeader"><a class="venueLink" href="./?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font></div>
								<div class="bands">'.$longBandList.'</div>
						</div>';
		}
		
		$bands = '';
		for($i=0;$i<=count($bandArray)-1;$i++){
			$bands.=$bandArray[$i];
			if($i != count($bandArray)-1){
				$bands.=', ';
			}
		}
		$titleString = ucfirst($venueLink).' '.date_format($day,'n/j').' - '.$bands;
		
		$pageBody.= '<script>
					var shareConfig = config = config = {
								  ui: {
								    flyout:            "middle right",
								    button_text:       "SHARE!"
								  },
								  networks:{
									email: {
										title: "'.$titleString.'",
										description: window.location.href
									},
									pinterest: {
								    	image:  "http://hellashows.com/images/hand-g.png"
								    }  
								  }
								}
				</script>';
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HellaShows - Show Information</title>
<meta name="description" content="<?php echo $titleString; ?>">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="js/velocity.js"></script>
<script src="js/share.min.js"></script>
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="./apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="./apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="./apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="./favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="./favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="./favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="./favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="./favicon-128.png" sizes="128x128" />
<meta name="application-name" content="HellaShows"/>
<meta name="msapplication-TileColor" content="#C0362C" />
<meta name="msapplication-TileImage" content="./mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="./mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="./mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="./mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="./mstile-310x310.png" />
<meta name="msapplication-notification" content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=http://www.hellashows.com/rss.php&amp;id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=http://www.hellashows.com/rss.php&amp;id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=http://www.hellashows.com/rss.php&amp;id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=http://www.hellashows.com/rss.php&amp;id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=http://www.hellashows.com/rss.php&amp;id=5;cycle=1" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
<link type="text/css" rel="stylesheet" href="css/jquery-ui.structure.min.css" />
<link type="text/css" rel="stylesheet" href="css/jquery-ui.min.css" />
<link type="text/css" rel="stylesheet" href="css/dates.css" />
<script>
	function adjustHeights(elem) {
		var fontstep = 1;
		if ($(elem).height()>$(elem).parent().height() || $(elem).width()>$(elem).parent().width()) {
			$(elem).css('font-size',(($(elem).css('font-size').substr(0,2)-fontstep)) + 'px').css('line-height',(($(elem).css('font-size').substr(0,2))) + 'px');
			adjustHeights(elem);
		}
	}
	
	jQuery(document).ready(function($){
		$('.bands').each(function() {adjustHeights($(this));});
		$( "#from" ).datepicker({
			changeMonth: true,
			dateFormat: 'm/d/y', 
			numberOfMonths: 2,
			onClose: function( selectedDate ) {
				if($('#to').val() != 'To'){
					$( "#to" ).datepicker( "option", "minDate", selectedDate);						
				}
			}
		});
		$( "#to" ).datepicker({
			changeMonth: true,
			dateFormat: 'm/d/y',
			numberOfMonths: 2,
			onClose: function( selectedDate ) {
				if($('#from').val() != 'From'){
					$( "#from" ).datepicker( "option", "maxDate", selectedDate );						
				}
			}
		});
					
		$("#pit").click(function(){
			switch($('#pit').val()){
				case 'off':
					$("#pit").removeClass('off');
					$("#pit").addClass('onlyPits');
					$('#pit').val('onlyPits');
					$('#pitHand').attr('src','images/hand-w.png');
					break;
				case 'onlyPits':
					$("#pit").removeClass('onlyPits');
					$("#pit").addClass('noPits');
					$('#pit').val('noPits');
					$('#pitHand').attr('src','images/hand-no.png');
					break;
				case 'noPits':
					$("#pit").removeClass('noPits');
					$("#pit").addClass('off');
					$('#pit').val('off');
					$('#pitHand').attr('src','images/hand-g.png');
					break;
				default:
			}			
		});
		
		$("#logo").click(function(){
			$("#menu").slideToggle("fast","swing");
			if($("#logo").html() == 'HS'){
				$("#logo").html('<font id="closeMenu">&#10006;</font>');
				$("#logo").addClass('activeLogo');
			}
			else{
				$("#logo").html('HS');
				$("#logo").removeClass('activeLogo');
			}
		});
		
		$("#backToTop").click(function(){
			$('html, body').animate({scrollTop : 0},1800,'easeInOutCubic');
		});
		
		$("#searchButton").click(function(){
			if($('#pit').val() == 'off'){
				$('#pit').prop('disabled',true);
			}
			if($('#search').val() == ''){
				$('#search').prop('disabled',true);
			}
			if($('#from').val() == 'From' || $('#from').val() == ''){
				$('#from').prop('disabled',true)
			}
			if($('#to').val() == 'To' || $('#to').val() == ''){
				$('#to').prop('disabled',true)
			}
			$('#searchForm').submit();
		});
		
		$("#search").keypress(function(event) {
		    if (event.which == 13) {
		        event.preventDefault();
		        $("#searchButton").click();
		    }
		});
		
		var expanded = false;
		
		$("#seeOriginal").click(function(event){
			if($('#originalString').css("width") == "0px"){
				if($('#stringText').html().length > 250){
					$('#originalString').css("width","758px");
					setTimeout("$('#originalString').css('height','100px');",900);
					expanded = true;	
				}
				else{
					$('#originalString').css("width","758px");
				}
				$('#hammerIcon').css("background-position","center bottom");
			}
			else{
				if(expanded == true){
					$('#originalString').css("height","48px");
					setTimeout("$('#originalString').css('width','0px');",900);
					expanded = false;
				}
				else{
					$('#originalString').css("width","0px");	
				}
				$('#hammerIcon').css("background-position","center top");
			}
		});
		
    });
</script>
</head>

<body>
<div id="header">
	<div id="headerContainer">
		<div id="logo">HS</div>
		<form action="./" method="get" id="searchForm">
			<input type="text" id="search" name="search" />
			<div id="dateOptions">
				<input type="text" id="from" name="from" value="From" />
				<input type="text" id="to" name="to"  value="To" />
			</div>
			<div id="ageOptions">
				<input type="checkbox" name="allAges" id="allAges" />
				<label for="allAges"></label>
				
				<input type="checkbox" name="sixteenPlus" id="sixteenPlus" />
				<label for="sixteenPlus"></label>
				
				<input type="checkbox" name="eighteenPlus" id="eighteenPlus" />
				<label for="eighteenPlus"></label>
				
				<input type="checkbox" name="twentyonePlus" id="twentyonePlus" />
				<label for="twentyonePlus"></label>
			</div>
			<div id="otherOptions">
				<input type="checkbox" name="free" id="free" />
				<label for="free"></label>
				<input type="text" class="off" name="pit" id="pit" value="off" />
				<label for="pit"><img id="pitHand" src="images/hand-g.png" style="width: 13px; height: 13px;" /></label>	
			</div>
		</form>
		<div id="searchButton">SEARCH</div>
	</div>
</div>
<div style="height: 63px; width: 100%;"></div>
<div id="menu">
	<div style="color:#fff; overflow:hidden; width: 180px; margin: 0px auto;">
		<a href="./" id="homeLink">HOME</a>
		<a href="./about/" id="aboutLink">ABOUT</a>
		<a href="#" id="backToTop">BACK TO THE TOP</a>
	</div>
</div>
<?php echo $pageBody; ?>
<div id="showFooter">
	<div class="shareButton"></div>
	<div id="seeOriginal"><div id="hammerIcon"></div></div>
	<!-- Obliterations (L.A.) / Torch Runner (Greensboro / N.C.) / Creative Adult / Culture Abuse / -->
	<div id="originalString"><font id="stringTitle">ORIGINAL STRING</font><br /><font id="stringText"><?php echo $sourceString; ?></font></div>
</div>
<script>
	new Share(".shareButton", shareConfig);
	$(".entypo-export").click(function(){
		if($(".social").hasClass("active")){
			$(".entypo-export").css("color","#fafafa");
		}
		else{
			$(".entypo-export").css("color","#333");
		}
	});

</script>
<div id="footer"></div>
</body>
</html>