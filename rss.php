<?php
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">

<channel>
	<title>HellaShows</title>
	<link>http://www.hellashows.com/rss.php</link>
	<description>A Handy Guide to the Bay Area Music Scene</description>
	<image>
		<url>./apple-touch-icon-60x60.png</url>
		<title>HellaShows</title>
		<link>http://www.hellashows.com</link>
	</image>';
	
	$dir = 'sqlite:shows.db';
    $dbh  = new PDO($dir) or die("cannot open the database");
	$query =  "SELECT DAY,VENUE,BANDS,PRICE,AGE,PIT,NO_INOUT,DRINK_TICKETS,RECOMMENDED,TIME,SHORT_URL from show_list WHERE DAY <= datetime('now', '+20 days') AND DAY >= strftime('%Y-%m-%d', datetime('now','localtime'))";
	
	$stmt = $dbh->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();	
	foreach ($result as $row)
    {
		$bandArray = explode('|@|',$row[2]);
		$i = 1;
		$limit = count($bandArray);
		$bandString = '';
		
		foreach($bandArray as $band){
			if($i != $limit){
				$bandString.=$band.', ';	
			}
			else{
				$bandString.=$band;
			}
			$i++;
		}
		
		$day = date_create($row[0]);
		$venue = ucfirst($row[1]);
		$showAttributes = array();
		if($row[9]!=''){
			if(strpos($row[9],'/') == true){
				$showTimes = explode('/',$row[9]);
				array_push($showAttributes,'Doors at '.$showTimes[0].', Show at '.$showTimes[1]);
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
				array_push($showAttributes,$prices[0].' pre-sale, '.$prices[1].' at the door');
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
				$showString.=', ';
			}
		}
		
		$dayString = date_format($day,'n/j');
		
		echo '	
	<item>
		<title>'.$dayString.': '.htmlspecialchars($bandString).'</title>
		<link>http://www.hellashows.com/show.php?id='.$row[10].'</link>
		<description>'.htmlspecialchars($venue).', '.htmlspecialchars($showString).'</description>
	</item>';		
		
	}				
	echo '
</channel>
</rss>';
?>