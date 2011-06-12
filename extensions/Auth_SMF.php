<?php

function Auth_SMF( $user, &$result ) {
	require_once $GLOBALS['SMF_SSIPath'];

	if ($context['user']['is_logged'] && ($context['user']['username'] != null)) {
		$username = User::getCanonicalName($context['user']['username'], 'creatable');
		$userId = User::idFromName($username);
		if ($userId != null) {
			$user->setId($userId);
		} else {
			# Create new local user account
			$user->loadDefaults($username);
			$user->setRealName($context['user']['name']);
			$user->setEmail($context['user']['email']);
			$user->setEmailAuthenticationTimestamp(wfTimestampNow());
			$user->setOption('rememberpassword', 1); # implicitly loads other default options
			$user->addToDatabase();
		}

		if ($user->loadFromDatabase()) {
			$user->saveToCache();
			$result = true;
			
			# Determine if User is Trusted and if not are they ready to be promoted.
			if (!in_array('trusted', $user->getGroups())) {
				if ($user_info['posts'] >= $GLOBALS['SMF_TrustedPostCount']) {
					$user->addGroup('trusted');
				}
			}
		}
	} else {
?>
<!--
<html>
	<head>
		<title>Redirecting...</title>
		<meta http-equiv="refresh" content="5;url=<?= $GLOBALS['SMF_LoginURL'] ?>">
	</head>
	<body style="width: 500px; text-align: center; margin: 60px auto;">
		<h2>Access Denied!</h2>
		<hr />
		<h3>Please login at the forums and then return to the wiki.</h3>
		<h4>Redirecting you to the forum login in 5 seconds...</h4>
	</body>
</html>
-->
<html>
	<head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('iframe').load(function() {
					if ($($('iframe')[0].contentWindow.document.getElementById('upper_section')).find('.greeting>span').length == 1) {
						window.location.reload();
					}
				});
			});
		</script>
	</head>
	<body style="width: 960px; text-align: center; margin: 60px auto;">
		<h3>You are currently not logged in. Please login below to continue.</h3>
		<h5>If after you login below you are not automattically redirected, please <a href="javascript:window.location.reload()">click here</a> to continue.</h5>
		<hr />
		<iframe src="<?= $GLOBALS['SMF_LoginURL'] ?>" width="960px" height="100%" frameborder="0"></iframe>
	</body>
</html>
<?php
		die();
	}
	return true;
}

?>
