<?php
    require_once("settings.inc");

    if(file_exists($config_file_path)){
		header("location: ".$application_start_file);
        exit;
	}

    ob_start();
    @phpinfo(-1);
    $phpinfo = array('phpinfo' => array());
    if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
	foreach($matches as $match){
		$array_keys = array_keys($phpinfo);
		$end_array_keys = end($array_keys);
		if(strlen($match[1])){
			$phpinfo[$match[1]] = array();
		}else if(isset($match[3])){
			$phpinfo[$end_array_keys][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
		}else{
			$phpinfo[$end_array_keys][] = $match[2];
		}
	}
	$php_core_index = ((version_compare(phpversion(), '5.3.0', '<'))) ? 'PHP Core' : 'Core';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Installation Guide</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="ApPHP Company - Advanced Power of PHP">
    <meta name="generator" content="ApPHP EasyInstaller">
	<link rel="stylesheet" type="text/css" href="img/styles.css">
</head>
<body text=#000000 vlink=#2971c1 alink=#2971c1 link=#2971c1 bgcolor=#ffffff>
<br><br>
<table align="center" width="70%" cellspacing=0 cellpadding=2 border=0>
<tbody>
<tr>
    <td class=text valign=top>
        <h2>New Installation of <?php echo $application_name;?>!</h2>

        Follow the wizard to setup your database.<BR><BR>
        <table width="100%" cellspacing=0 cellpadding=0 border=0>
        <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing=0 cellpadding=0 border=0>
                <tbody>
                <tr>
                    <td></td>
                    <td align=middle>

                        <table width="100%" cellspacing=0 cellpadding=0 border=0>
                        <tbody>
                        <tr>
                            <td class=text align=left><b>Getting System Info</b></td>
                        </tr>
                        </tbody>
                        </table>
                        <br />

						<table class="central" width="100%" cellspacing=0 cellpadding=0 border=0>
                        <tbody>
                        <tr>
                            <td class=text align=left>
                                <ul style="padding-left:0px;">
									<li>System: <?php echo $phpinfo['phpinfo']['System'];?></li>
                                    <li>PHP version: <?php echo function_exists('phpversion') ? phpversion() : ''; ?></li>
                                    <li>Server API: <?php echo $phpinfo['phpinfo']['Server API'];?></li>
                                    <li>Safe Mode: <?php echo $phpinfo[$php_core_index]['safe_mode'][0];?></li>
								</ul>
							</td>
                        </tr>
						</tbody>
                        </table>
						<br />

                        <table class="text" width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td align='left'>
								Click on Start button to continue.<br><br>
                                <input type="button" class="form_button" value="Start" name="submit" title="Click to start installation" onclick="window.location.href='install.php'">
                            </td>
						</tr>
                        </table>

					</TD>
                    <TD></TD>
                </TR>
                </TBODY>
                </TABLE>

            </TD>
        </TR>
        </TBODY>
        </TABLE>
		<br />

        <?php include_once("footer.php"); ?>
    </TD>
</TR>
</TBODY>
</TABLE>

</body>
</html>
