$wgGroupPermissions['*']['edit'] = false;
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['read'] = false;
$wgGroupPermissions['user']['edit'] = false;
$wgGroupPermissions['trusted']['edit'] = true;

require_once 'extensions/Auth_SMF.php';
$wgHooks['UserLoadFromSession'][] = 'Auth_SMF';
$SMF_SSIPath = '/home/haymanhosting/public_html/smf/SSI.php';
$SMF_LoginURL = 'http://haymanhosting.com/smf/index.php?action=login';
$SMF_TrustedPostCount = 500;
