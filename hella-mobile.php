<?php
	$ageValues = array();
	$sqlArgs = array();
	$dir = 'sqlite:../../shows.db';
    $dbh  = new PDO($dir) or die("cannot open the database");
	$query =  "SELECT DAY,VENUE,BANDS,PRICE,AGE,PIT,NO_INOUT,DRINK_TICKETS,RECOMMENDED,TIME,SHORT_URL from show_list";
	$free = $_GET['free'];
	$noArgsGiven = 0;
	$browsePage = 0;
	$pitValue = 'off';
	$pitHand = 'hand-g.png';
	$fromValue = 'From';
	$toValue = 'To';
	
	if(count($_GET) == 0){
		$query.=" WHERE DAY >= strftime('%Y-%m-%d', datetime('now','-1 hours'))";
	}
	else $titleAddendum = ' - Search Results';
	
	if(count($_GET) > 0){
		//Search + Search-Like Functions
		if(isset($_GET['venue'])){
			array_push($sqlArgs," WHERE VENUE LIKE :venueName");
			$queryVenue = "%".$_GET['venue']."%";
			$browsePage = 1;
			$titleAddendum = ' - Shows at '.ucfirst($_GET['venue']);
		}
		elseif(isset($_GET['city'])){
			array_push($sqlArgs," WHERE CITY LIKE :venueName");
			$queryCity = "%".$_GET['city']."%";
			$browsePage = 1;
		}
		elseif(isset($_GET['band'])){
			$searchValue = $_GET['band'];
			array_push($sqlArgs," WHERE BANDS LIKE :bandName");
			$queryBand = "%".$_GET['band']."%";
			$titleAddendum = ' - Shows with '.$_GET['band'];
		}
		elseif(isset($_GET['search'])){
			$searchValue = $_GET['search'];
			array_push($sqlArgs," WHERE (VENUE LIKE :searchString OR BANDS LIKE :searchString)");
			$querySearch = "%".$_GET['search']."%";
		}
		else{
			array_push($sqlArgs," WHERE ");
			$noArgsGiven = 1;
		}
		
		
		if(isset($_GET['from'])){
			$fromDate = $_GET['from'];
			$fromDate = strtotime($fromDate);
			$fromDate = date('Y-m-d',$fromDate);
			array_push($sqlArgs,"DAY >= '".$fromDate."'");
			$fromValue = $_GET['from'];
		}
		if(isset($_GET['to'])){
			$toDate = $_GET['to'];
			$toDate = strtotime($toDate);
			$toDate = date('Y-m-d',$toDate);
			array_push($sqlArgs,"DAY <= '".$toDate."'");
			$toValue = $_GET['to'];
		}
		
		if(isset($_GET['previousShows'])){
			array_push($sqlArgs,"DAY < strftime('%Y-%m-%d', datetime('now','-1 hours'))");
			$orderDescend = true;
		}
		else{
			array_push($sqlArgs,"DAY >= strftime('%Y-%m-%d', datetime('now','-1 hours'))");
		}
		
		//Gets Free
		if($free == 'on'){
			array_push($sqlArgs,"PRICE = 'free'");
			$freeValue = ' checked';
		}
		
		//Gets Pit Value
		if(isset($_GET['pit'])){
			$pit = $_GET['pit'];
			if($pit == 'noPits'){
				array_push($sqlArgs,'PIT = 0');
				$pitValue = 'noPits';
				$pitHand = 'hand-no.png';
			}
			elseif($pit == 'onlyPits'){
				array_push($sqlArgs,'PIT = 1');
				$pitValue = 'onlyPits';
				$pitHand = 'hand-w.png';
			}
		}
		
		//Gets Ages and pushes them in
		if(isset($_GET['allAges'])){
			array_push($ageValues,'All Ages');
			$allAgesValue = ' checked';
		}
		if(isset($_GET['sixteenPlus'])){
			array_push($ageValues,'16+');
			$sixteenPlusValue = ' checked';
		}
		if(isset($_GET['eighteenPlus'])){
			array_push($ageValues,'18+');
			$eighteenPlusValue = ' checked';
		}
		if(isset($_GET['twentyonePlus'])){
			array_push($ageValues,'21+');
			$twentyonePlusValue = ' checked';
		}
		
		
		
		//Output: Formatting the Age Query sepatately and pushing it into the args
		$agesLength = count($ageValues);
		if($agesLength > 0){
			$ageQuery = ' (';
			for($i = 0; $i < $agesLength; $i++){
				if(strlen($ageValues[$i]) > 0){
					$ageQuery.="AGE = '".$ageValues[$i]."'";
				}
				if($i != $agesLength-1){
					$ageQuery.=' OR ';
				}
			}
			$ageQuery.=')';
			array_push($sqlArgs,$ageQuery);
		}
		
		//Output: Appends the argument set to the string
		$argsLength = count($sqlArgs);
		for($i = 0; $i < $argsLength; $i++){
			if($i != 0 && $i != $noArgsGiven){
				$query.=' AND ';
			}
			$query.=$sqlArgs[$i];
		}
		
		if($orderDescend == true){
			$query.=' ORDER BY DAY DESC';
		}
	}			
	$stmt = $dbh->prepare($query);
	if(isset($queryVenue)){$stmt->bindParam(':venueName',$queryVenue,PDO::PARAM_STR);}
	if(isset($queryCity)){$stmt->bindParam(':cityName',$queryCity,PDO::PARAM_STR);}
	if(isset($queryBand)){$stmt->bindParam(':bandName',$queryBand,PDO::PARAM_STR);}
	if(isset($querySearch)){$stmt->bindParam(':searchString',$querySearch,PDO::PARAM_STR);}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HellaShows<?php echo $titleAddendum; ?></title>
<meta name="description" content="HellaShows: A Handy Guide to the Bay Area Music Scene" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/velocity.js"></script>
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
<link type="text/css" rel="stylesheet" href="css/mobile.css" />
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
			if($('#extras').css('display') != 'none'){
				$("#extras").css("display","none");
				$("#mobileOptions").removeClass('activeMobileOptions');
				$("#optionsIcon").css('background-position','top');
				$("#header").css('border-width','10px');
			}
			$("#menu").slideToggle("fast","swing");
			if($("#logo").html() == 'HS'){
				$("#logo").html('<div id="closeMenu"></div>');
				$("#logo").addClass('activeLogo');
			}
			else{
				$("#logo").html('HS');
				$("#logo").removeClass('activeLogo');
			}
		});
		
		$("#mobileOptions").click(function(){
			if($('#menu').css('display') != 'none'){
				$('#menu').css('display','none');
				$("#logo").html('HS');
				$("#logo").removeClass('activeLogo');
			}
			if($("#extras").css('display') == 'none'){
				$("#mobileOptions").addClass('activeMobileOptions');
				$("#optionsIcon").css('background-position','bottom');
				$("#header").css('border-width','130px');
			}
			else{
				$("#mobileOptions").removeClass('activeMobileOptions');
				$("#optionsIcon").css('background-position','top');
				$("#header").css('border-width','10px');
			}
			$("#extras").slideToggle("fast","swing");
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
		
		$("#mapToggler").click(function(){
			if ( $('#mapContainer').is( ":hidden" ) ) {
				$('#mapContainer').velocity("slideDown", { duration: 700 });
				$('#mapToggler').html('HIDE MAP');
			} 
			else{
				$('#mapContainer').velocity("slideUp", { duration: 700 });
				$('#mapToggler').html('SHOW MAP');
			}
		});
		$(".container").mouseenter(function(){
			$(this).find('.showInfo').css("width","150px");
		});
		$(".container").mouseleave(function(){
			$(this).find('.showInfo').css("width","0px");
		});
    });
</script>
</head>

<body>

<div id="header">
	<div id="headerContainer" style="width:960px; margin:0px auto;">
		<div id="logo">HS</div>
		<form action="" method="get" id="searchForm">
			<input type="text" id="search" name="search" value="<?php echo $searchValue; ?>" />
			<div id="mobileOptions"><div id="optionsIcon"></div></div>
           <div id="extras">
                <div id="dateOptions">
                    <input type="date" id="from" name="from" value="<?php echo $fromValue; ?>" />
                    <input type="date" id="to" name="to"  value="<?php echo $toValue; ?>" />
                </div>
                <div id="ageOptions">
                    <input type="checkbox" name="allAges" id="allAges" <?php echo $allAgesValue; ?>/>
                    <label for="allAges"></label>
                    
                    <input type="checkbox" name="sixteenPlus" id="sixteenPlus" <?php echo $sixteenPlusValue; ?>/>
                    <label for="sixteenPlus"></label>
                    
                    <input type="checkbox" name="eighteenPlus" id="eighteenPlus" <?php echo $eighteenPlusValue; ?>/>
                    <label for="eighteenPlus"></label>
                    
                    <input type="checkbox" name="twentyonePlus" id="twentyonePlus" <?php echo $twentyonePlusValue; ?>/>
                    <label for="twentyonePlus"></label>
                </div>
                <div id="otherOptions">
                    <input type="checkbox" name="free" id="free" <?php echo $freeValue; ?>/>
                    <label for="free"></label>
                    <input type="text" class="<?php echo $pitValue; ?>" name="pit" id="pit" value="<?php echo $pitValue; ?>" />
                    <label for="pit"><img id="pitHand" src="images/<?php echo $pitHand; ?>" style="width: 68px; height: 68px; position: relative; top: 13px;" id="pitButton" /></label>	
                </div>
            </form>
            <div id="searchButton">SEARCH</div>
         </div>
	</div>
</div>
<div style="height: 140px; width: 100%;"></div>
<div id="menu">
	<div style="color:#fff; overflow:hidden; width: 480px; margin: 0px auto;">
		<a href="./" id="homeLink">HOME</a>
		<a href="./about/" id="aboutLink">ABOUT</a>
		<a href="#" id="backToTop">BACK TO THE TOP</a>
	</div>
</div>

<?php		
			//executes the SQL statement
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
			
			if(isset($_GET['previousShows'])){
				$brightPrevious = '-bright';
				$brightUpcoming = '';
			}	
			else{
				$brightPrevious = '';
				$brightUpcoming = '-bright';
			}
			
			if(isset($_GET['venue']) || isset($_GET['city'])){		
				echo 	'<div id="mapContainer" style="">
							<iframe
								style="transform: scale(1.92); -moz-transform: scale(1.92); -webkit-transform: scale(1.92); margin-top: 136px;"
								width="500"
								height="299"
								frameborder="0" style="border:0;margin: 0px auto;"
								src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA91BKt_0yqc1NOq_IIORyY1ydbjsuMIkI
								&q='.$mapString.'
								&attribution_source=Google+Maps+Embed+API
								&attribution_ios_deep_link_id=comgooglemaps://?daddr='.$mapString.'">
							</iframe>
						 </div>
						 <div style="margin: 0px auto; width: 960px;">
						 	<a href="#" id="mapToggler">HIDE MAP</a>
						 	<a href="?venue='.urlencode($_GET['venue']).'" id="upcomingShows'.$brightUpcoming.'">UPCOMING SHOWS</a>
						 	<a href="?venue='.urlencode($_GET['venue']).'&previousShows=on" id="previousShows'.$brightPrevious.'">PAST SHOWS</a>
						 </div>';
			}
			elseif(count($_GET) == 0){
				echo '<div id="title">HELLA<font color="#999999">SHOWS</font></div>';
				$browsePage = 1;
			}
			
			$previousString = str_replace('&previousShows=on','',$_SERVER['QUERY_STRING']);
			if(substr_count($_SERVER['QUERY_STRING'],'&previousShows=on') == 0){
				$upcomingString = $_SERVER['QUERY_STRING'].'&previousShows=on';}	
			else{
				$upcomingString = $_SERVER['QUERY_STRING'];
			}
			$previousString = str_replace('?', '', $previousString);
			$upcomingString = str_replace('?', '', $upcomingString);
			if(count($result) == 0){
				echo '<div style="margin: 20px auto 0px auto; width: 960px;">
						 	<div id="resultsLabel">RESULTS</div>
						 	<a href="./?'.$previousString.'" id="upcomingShows'.$brightUpcoming.'">UPCOMING SHOWS</a>
						 	<a href="./?'.$upcomingString.'" id="previousShows'.$brightPrevious.'">PAST SHOWS</a>
					  </div>';
				echo '<div id="noResults"><font id="noResultsLine1">NO RESULTS FOUND</font><font id="noResultsLine2">SORRY ABOUT THAT.</font></div>';
			}
			elseif($browsePage == 0){
				
				$previousString = str_replace('&previousShows=on','',$_SERVER['QUERY_STRING']);
				if(substr_count($_SERVER['QUERY_STRING'],'&previousShows=on') > 0){$upcomingString = $_SERVER['QUERY_STRING'];}
				else{$upcomingString = $_SERVER['QUERY_STRING'].'&previousShows=on';}
				
				echo '<div style="margin: 20px auto 0px auto; width: 960px;">
						 	<div id="resultsLabel">RESULTS</div>
						 	<a href="./?'.$previousString.'" id="upcomingShows'.$brightUpcoming.'">UPCOMING SHOWS</a>
						 	<a href="./?'.$upcomingString.'" id="previousShows'.$brightPrevious.'">PAST SHOWS</a>
					  </div>';
			}
			
			
			foreach ($result as $row)
            {
	            $row[2] = str_replace('cancelled:', 'Cancelled:|@|', $row[2]);
				$row[2] = str_replace('postponed:', 'Postponed:|@|', $row[2]);
				$venueLink = current(explode(",", $row[1]));
				$bandArray = explode('|@|',$row[2]);
				$bandNum = count($bandArray);
				$day = date_create($row[0]);
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
					elseif($row[4] == '16+'){array_push($showAttributes,'16 up');}
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
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="onlyBand'.$longOrNot.'">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a></div>
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
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands2Column oneBand'.$col1length.'">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a></div>
									</div>
									<div class="bands2Column oneBand'.$col2length.' padded">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a></div>
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
						
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands2Column oneBand'.$col1length.'">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a></div>
									</div>
									<div class="bands2Column twoBands'.$col2length.' padded">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
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
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands2Column twoBands'.$col1length.'">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a></div>
									</div>
									<div class="bands2Column twoBands'.$col2length.' padded">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a></div>
									</div>
								</div>';
								break;
					case 5:
						if(strlen($bandArray[0])>=17 || strlen($bandArray[1]) >=17){
							$col1length='-long';
						}
						else $col1length='';
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands2Column twoBands'.$col1length.'">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a></div>
									</div>
									<div class="bands2Column threeBands padded">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a></div>
									</div>
								</div>';
								break;
					case 6:
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands2Column threeBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
									</div>
									<div class="bands2Column threeBands padded">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a></div>
									</div>
								</div>';
								break;
					case 7:
						if(strlen($bandArray[0])>=20 || strlen($bandArray[1]) >=20 || strlen($bandArray[2]) >=20){
							$col1length='-long';
						}
						else $col1length='';
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands2Column threeBands'.$col1length.'">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
									</div>
									<div class="bands2Column fourBands">
										<div class="bands fourBands padded"><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a></div>
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
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands2Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a></div>
									</div>
									<div class="bands2Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a></div>
									</div>
								</div>';
								break;
					case 9:
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands3Column threeBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band=">'.urlencode($bandArray[1]).'>'.$bandArray[1].'</a><br /><a class="bandLink" href="?band=">'.urlencode($bandArray[2]).'>'.$bandArray[2].'</a></div>
									</div>
									<div class="bands3Column threeBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="?band=">'.urlencode($bandArray[4]).'>'.$bandArray[4].'</a><br /><a class="bandLink" href="?band=">'.urlencode($bandArray[5]).'>'.$bandArray[5].'</a></div>
									</div>
									<div class="bands3Column threeBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="?band=">'.urlencode($bandArray[7]).'>'.$bandArray[7].'</a><br /><a class="bandLink" href="?band=">'.urlencode($bandArray[8]).'>'.$bandArray[8].'</a></div>
									</div>
								</div>';
								break;
					case 10:
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands3Column threeBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
									</div>
									<div class="bands3Column threeBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a></div>
									</div>
									<div class="bands3Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[8]).'">'.$bandArray[8].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[9]).'">'.$bandArray[9].'</a></div>
									</div>
								</div>';
								break;
					case 11:
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="showHeader">'.ucfirst($row[1]).'<font class="attributes">'.$showString.'</font></div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands3Column threeBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a></div>
									</div>
									<div class="bands3Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a></div>
									</div>
									<div class="bands3Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[8]).'">'.$bandArray[8].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[9]).'">'.$bandArray[9].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[10]).'">'.$bandArray[10].'</a></div>
									</div>
								</div>';
								break;
					case 12:
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
									<div class="bands3Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[0]).'">'.$bandArray[0].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[1]).'">'.$bandArray[1].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[2]).'">'.$bandArray[2].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[3]).'">'.$bandArray[3].'</a></div>
									</div>
									<div class="bands3Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[4]).'">'.$bandArray[4].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[5]).'">'.$bandArray[5].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[6]).'">'.$bandArray[6].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[7]).'">'.$bandArray[7].'</a></div>
									</div>
									<div class="bands3Column fourBands">
										<div class="bands"><a class="bandLink" href="?band='.urlencode($bandArray[8]).'">'.$bandArray[8].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[9]).'">'.$bandArray[9].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[10]).'">'.$bandArray[10].'</a><br /><a class="bandLink" href="?band='.urlencode($bandArray[11]).'">'.$bandArray[11].'</a></div>
									</div>
								</div>';
								break;
					default:
						$longBandList = '';
						for($i=0; $i<=11; $i++){
							$longBandList.='<a class="bandLink" href="?band='.urlencode($bandArray[$i]).'">'.$bandArray[$i].'</a> ';
						}
						$theRestOfTheBands = count($bandArray) - 12;
						$longBandList.='<a class="longListLink" href="./show.php?id='.$row[10].'">+ '.$theRestOfTheBands.' more</a>';
						echo '	<div class="container">
									<div class="blackbox">
										<div class="dateBox">
											<div class="d'.date_format($day,'n-j').' theDate">'.date_format($day,'n/j').'</div>
											<div class="'.$dayClass.'">'.$dayString.'</div>
										</div>
									</div>
									<div class="showHeader"><a class="venueLink" href="?venue='.urlencode($venueLink).'">'.ucfirst($row[1]).'</a><font class="attributes">'.$showString.'</font><div class="showInfo"><a href="./show.php?id='.$row[10].'" class="infoLink">INFO</a></div></div>
										<div class="bands"><div class="longBandList">'.$longBandList.'</div></div>
								</div>';
				}
            }
        if(count($result) == 1){
	    	echo '<div style="width:100%; height: 670px;"></div>';
        }
        elseif(count($result) == 2){
	    	echo '<div style="width:100%; height: 520px;"></div>';
        }
        ?>
<div id="footer"></div>
</body>
</html>