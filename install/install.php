<?php
    require_once("settings.inc");

    if(file_exists($config_file_path)){
		header("location: ".$application_start_file);
        exit;
	}

	$database_name = isset($_POST['database_name']) ? strip_tags($_POST['database_name']) : '';
	$database_username = isset($_POST['database_username']) ? strip_tags($_POST['database_username']) : '';
	$database_password = isset($_POST['database_password']) ? strip_tags($_POST['database_password']) : '';

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
        <H2>New Installation of <?php echo $application_name;?>!</H2>

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
                            <td class=text align=left><b>Step 1. Database Import</b></td>
                        </tr>
                        </tbody>
                        </table>
                        <br />

                        <form method="post" action="install2.php">
                        <input type="hidden" name="submit" value="step2" />
                        <table class="central text" width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
                        <tr>
                            <tr>
                                <td>&nbsp;Database Host</td>
                                <td>
                                    <input type="text" class="form_text" name="database_host" value='localhost' size="30">
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;Database Name</td>
                                <td>
                                    <input type="text" class="form_text" name="database_name" size="30" value="<?php echo $database_name; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;Database Username</td>
                                <td>
                                    <input type="text" class="form_text" name="database_username" size="30" value="<?php echo $database_username; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;Database Password</td>
                                <td>
                                    <input type="password" class="form_text" name="database_password" size="30" value="<?php echo $database_password; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2>&nbsp;</td>
                            </tr>
                        </table>
						<br>

                        <table width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
                            <tr>
                                <td colspan=2 align='left'>
									<input type="button" class="form_button" name="btn_cancel" value="Cancel" onclick="window.location.href='index.php'">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="submit" class="form_button" name="btn_submit" value="Continue">
                                </td>
                            </tr>
                        </table>

                        </form>
						<br />

					</td>
                    <td></td>
                </tr>
                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>

        <?php include_once("footer.php"); ?>
    </td>
</tr>
</tbody>
</table>

</body>
</html>
