<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HellaShows - About</title>
<meta name="description" content="HellaShows: A Handy Guide to the Bay Area Music Scene" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
<script src="../js/velocity.js"></script>
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="./apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="./apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="./apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="../favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="../favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="../favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="../favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="../favicon-128.png" sizes="128x128" />
<meta name="application-name" content="HellaShows"/>
<meta name="msapplication-TileColor" content="#C0362C" />
<meta name="msapplication-TileImage" content="../mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="../mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="../mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="../mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="../mstile-310x310.png" />
<meta name="msapplication-notification" content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=http://www.timknapton.com/sandbox/hs/rss.php&amp;id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=http://www.timknapton.com/sandbox/hs/rss.php&amp;id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=http://www.timknapton.com/sandbox/hs/rss.php&amp;id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=http://www.timknapton.com/sandbox/hs/rss.php&amp;id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=http://www.timknapton.com/sandbox/hs/rss.php&amp;id=5;cycle=1" />
<link type="text/css" rel="stylesheet" href="../css/style.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery-ui.structure.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery-ui.min.css" />
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
					$('#pitHand').attr('src','../images/hand-w.png');
					break;
				case 'onlyPits':
					$("#pit").removeClass('onlyPits');
					$("#pit").addClass('noPits');
					$('#pit').val('noPits');
					$('#pitHand').attr('src','../images/hand-no.png');
					break;
				case 'noPits':
					$("#pit").removeClass('noPits');
					$("#pit").addClass('off');
					$('#pit').val('off');
					$('#pitHand').attr('src','../images/hand-g.png');
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
    });
</script>
</head>

<body>

<div id="header">
	<div id="headerContainer">
		<div id="logo">HS</div>
		<form method="get" id="searchForm" action="../">
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
				<label for="pit"><img id="pitHand" src="../images/hand-g.png" style="width: 13px; height: 13px;" id="pitButton" /></label>	
			</div>
		</form>
		<div id="searchButton">SEARCH</div>
	</div>
</div>
<div style="height: 63px; width: 100%;"></div>
<div id="menu">
	<div style="color:#fff; overflow:hidden; width: 180px; margin: 0px auto;">
		<a href="../" id="homeLink">HOME</a>
		<a href="./" id="aboutLink">ABOUT</a>
		<a href="#" id="backToTop">BACK TO THE TOP</a>
	</div>
</div>
<div style="color:#d1d1d1;" id="aboutPage">
	<div id="note"></div>
	<h1>THANKS FOR VISITING.</h1>
	<h2>About</h2>
	<p>HellaShows is here to help you find out what's happening around the Bay Area. You can use it to search, filter, explore, and share shows from all around the Bay and Northern California. Our data comes from Steve Koepke's (excellent) <a href="http://stevelist.com/">Bay Area Entertainment Guide</a>, where it is then packaged and delivered to your phone, tablet, or desktop through the magic of computers.</p>
	<h2>Why?</h2>
	<p>As <a href="https://facebook.com/ahumancostume" target="_blank">local musicians</a> and fans of live music, our team has seen firsthand how Steve's List has improved the Bay Area music community. That stated, we wanted to give something back and get The List in the hands of more people than ever.</p>
	<h2>How do I get my show onto the list?</h2>
	<p>HellaShows reads and interprets data from <a href="http://stevelist.com">The Steve List</a>. To get listed there, e-mail skoepke {at} stevelist dot com.</p>
	<h2>Anything else?</h2>
	<ul>
		<li><font class="aboutBold">Add hellashows.com to your homescreen:</font> You can add HellaShows to your mobile device's homescreen. 
			<ul>
				<li>iOS: Tap Safari's Share button and then "Add to Homescreen".</li>
				<li>Android: Tap "Add to Homescreen" in Chrome's App Menu.</li> 
				<li>Windows Phone: Tap the "More" menu in IE and then "Pin to Start".</li>
			</ul>
		<li><font class="aboutBold">We make no guarantees:</font> Since we're obtaining our data from a third party, we can't make any guarantees as to its accuracy. Sorry!</li>
		<li><font class="aboutBold">Check the Source String:</font> To see a show's string as it was interpreted by our parser, click the tools icon below the listing on an individual show's page.</li>
		<li><font class="aboutBold">We really like:</font> <a href="http://www.foopee.com/punk/the-list/">The Foopee List</a>, <a href="http://www.uncorp.net/list/index.html">Antonio's List</a>, <a href="http://www.jmarshall.com/events/">James' List</a>, <a href="http://jon.luini.com/thelist/date.html">Jon's List</a>, and <a href="http://www.revealinglive.com">RevealingLive</a></li>
		<li><font class="aboutBold">Get the RSS:</font> We also have an <a href="../rss.php">RSS Feed</a> for upcoming shows.</li>
	</ul>
	<h2>Thanks again from the Team:</h2>
	<p>- Matt and Tim (the Back- and Front-end departments, respectively)</p>
	<a href="https://www.facebook.com/hellashows" target="_blank"><h1>HS Facebook</h1></a>
</div>
<div id="footer"></div>
</body>
</html>
