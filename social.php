<?php

require_once 'lib/Alerts/Facebook.php';

$alertsFacebook = new Alerts_Facebook();

$facebookUser = $alertsFacebook->facebook->getUser();

if ($facebookUser) {
	try {
		$userProfile = $alertsFacebook->facebook->api('/me');
	} catch (FacebookApiException $e) {
		$facebookUser = null;
	}
}

if ($facebookUser) {
	$facebookUrl = $alertsFacebook->facebook->getLogoutUrl();
} else {
	$facebookUrl = $alertsFacebook->facebook->getLoginUrl();
}

?>
<!doctype html>
<html>
	
	<head>
		<meta charset="utf-8"/>
		<meta name="HandheldFriendly" content="true" />
		<meta name="MobileOptimized" content="320"/>
	 	<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Home - ESPN</title>
		<link href="vendor/bootstrap-2.2.2/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link rel='stylesheet' href='css/common.css'>
		<script src="vendor/js/handlebars-1.0.rc.1.js" type="text/javascript"></script>
		<script type="text/javascript" src="vendor/jquery-1.8.3.min.js"></script>
		<script src="vendor/js/jquery.pubsub.js" type="text/javascript"></script>
		<script src="js/common.js" type="text/javascript"></script>
		<script src="js/scoreUpdater.js" type="text/javascript"></script>
		<title>Social</title>
		<script type="text/javascript">
			$(document).ready(function() {

				var loggedInFacebook = <?php echo $facebookUser; ?> > 0;

				function postFacebookWall(message)
				{
					$.ajax({
						type: 'POST',
						url: '/espn-alerts-hackathon/send-facebook-alert.php',
						data: 'message=' + message,
						success: function(data) {}
					})
				}

				$.subscribe("/score/update", function( e, data ) {
					if (loggedInFacebook == true) {
						// postFacebookWall('');
					}
				});

				$.subscribe("/score/firstTime", function( e, competition ) {
					if (loggedInFacebook == true) {

						description = 'is now following the '
							+ competition.competitors[1].team.location
							+ ' ' + competition.competitors[1].team.name
							+ ' vs. ' + competition.competitors[0].team.location
							+ ' ' + competition.competitors[0].team.name
							+ ' game';

						postFacebookWall(description);
					}
				});
			});
		</script>
	</head>
	<body>
		<div class="container">
			<h1>Social</h1>

			<form method="POST">
				<div style="margin-top: 15px;">
				<?php if (! $facebookUser): ?>
					<a href="<?php echo $facebookUrl; ?>"><div style="width:150px;height:20px; background: url('image/facebook_signin.png'); background-position: 150px 185px;"></div></a>
				<?php endif; ?>
			</div>
				<div style="margin-top: 15px;">
					<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="none" data-size="large">Tweet Last</a>
				</div>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</form>
		</div>
	</body>
</html>