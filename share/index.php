<?php
	session_start();
	
	// include
	require_once('../model/Database.php');
	require_once('../model/AlphaIdGenerator.php');
	
	$id = $_GET['id'];

	$alphaIdGenerator = new AlphaIdGenerator();
	$alsterId = $alphaIdGenerator -> alphaId($id, true); // true get back number from alphaId (= alsterId)
	$alsterId = intval($alsterId);

	$db = new Database();
	$db->createDatabase('sqlite:../data/sillyPlayDB.sqlite');

	// get creation and member
	try {
		$stmt = $db->select('SELECT * FROM Alster WHERE alsterId = :alsterId', array(':alsterId' => $id));
		
		while ($row = $stmt->fetch()){
			$id = $row['alsterId'];
			$filename = $row['alsterUrl'];
		}
		if (count($filename) != 1) {
			header('location: pagenotfound.php');
		}

	} catch(PDOException $e) {
	    echo 'ERROR: ' . $e->getMessage();
	}

?>

<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/">
	<head>
		<meta charset="utf-8" />
		 <!-- CSS file -->
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/reset.css">
		<link rel="stylesheet" type="text/css" href="../css/share.css">
		<!-- Facebook-->
		<meta property="og:title" content="sillyPlay shared" />
		<meta property="og:description" content="sillyPlay." />
		<meta property="og:image" content="../alster/<?php echo $filename; ?>" />
		<!-- Google+ -->
		<meta itemprop="name" content="sillyPlay">
		<meta itemprop="description" content="sillyPlay.">
		<title>sillyPlay</title>
	</head>
	<body>
		<!-- FB -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<!-- FB end -->

	    <!-- NAVBAR -->
	    <nav class="navbar navbar-inverse navbar-fixed-top">
	      <div class="navbar-inner">
	        <div class="container">
	          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="brand" href="../index.php">sillyPlay</a>
	          <div class="nav-collapse collapse">
	          </div>

	        </div>
	      </div>
	    </nav>


		<div id="shareContainer">

			<div id="shareContent">
				<!-- left section -->
				<div id="leftSection">
					<h2 class="boxH2">Share this</h2>
					<div>
						<!-- Facebook -->
						<div class="shareServicePart">
							<div class="fb-like" data-send="false" data-layout="button_count" data-width="70" data-show-faces="false" data-colorscheme="dark"></div>
						</div>
						<!-- Twitter -->
						<div class="shareServicePart">
							<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>
						<div class="shareServicePart">
							<!-- Google +1 -->
							<g:plusone annotation="inline" width="120"></g:plusone>
							<script type="text/javascript">
							  (function() {
							    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
							    po.src = 'https://apis.google.com/js/plusone.js';
							    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
							  })();
							</script>
						</div>
						<!-- Tumblr -->
						<div class="shareServicePart">
							<a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a>
						</div>
					</div>
				</div>
				<!-- image -->
				<div id="theImage">
					<img src="../alster/<?php echo $filename; ?>" alt="" />
				</div>
				<!-- right section -->
				<div id="rightSection">
					<h2 class="boxH2 justify">This...</h2>
					<p class="justify">...was created using sillyPlay – the web application that lets you create cool images from pictures and quotes.</p>
				</div>
			</div>
			<div id="fb-commentsContainer" class="fb-comments" data-href="http://localhost/dev/webbteknik2_project/share/?id=<?php echo $id; //$alphaId; ?>" data-num-posts="3" data-width="470" data-colorscheme="dark"></div>
		</div>
		<div id="footer">&copy; 2013 sillyPlay</div>

		<!-- tumblr -->
		<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>
	</body>
</html>