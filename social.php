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

<html>
	<head>
		<title>Social</title>
		<link href="vendor/bootstrap-2.2.2/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="vendor/jquery-1.8.3.min.js"></script>
		<script type="text/javascript">
			Alerts = {
				init: function()
				{
					var self = this;

					$('.facebook-btn').on('click', function(event) {
						var messageText = escape($('input[name=message]').val());

						alert(messageText);

						$.ajax({
							type: 'POST',
							url: '/espn-alerts-hackathon/ifttt/send-facebook-alert.php',
							data: 'message=' + messageText,
							success: function(data) {
								alert('Sent successfully');
							}
						})
					});

					self.apiUrl = 'http://api.espn.com/v1/sports/basketball/mens-college-basketball/events/323530012';
					self.apiKey = 'k63bb77qjahygsjepf5qu7de';

					// window.setTimeout(self.poll, 10000);
					// self.poll();
				},
				poll: function()
				{
					$.ajax({
						type: 'GET',
						url: self.apiUrl + '?apikey=?' + self.apiKey,
						success: function(response) {

						},
					})
				}
			};
		</script>
	</head>
	<body>
		<div class="container">
			<?php if ($facebookUser): ?>
			<div><a href="<?php echo $facebookUrl; ?>">Logout of Facebook</a></div>
			<?php else: ?>
			<div><a href="<?php echo $facebookUrl; ?>">Login with Facebook</a></div>
			<?php endif; ?>

			<form method="POST">
				<input type="text" name="message" placeholder="Example Text" />
				<?php if ($facebookUser): ?>
				<button type="button" class="facebook-btn btn btn-primary">Send to Facebook</button>
				<?php endif; ?>
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="none">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</form>
		</div>
		<script type="text/javascript">
		Alerts.init();
		</script>
	</body>
</html>