<?php

    require_once("settings.inc");

    if(file_exists($config_file_path)){
		header("location: ".$application_start_file);
        exit;
	}

	$completed = false;
	$error_mg  = array();
	$submit = isset($_POST['submit']) ? stripcslashes($_POST['submit']) : '';

	if($submit != 'step2'){
		header('location: install.php');
        exit;
    }else{
		$database_host		= isset($_POST['database_host']) ? $_POST['database_host'] : "";
		$database_name		= isset($_POST['database_name']) ? $_POST['database_name'] : "";
		$database_username	= isset($_POST['database_username']) ? prepare_input($_POST['database_username']) : "";
		$database_password	= isset($_POST['database_password']) ? $_POST['database_password'] : "";

		if(empty($database_host)){
			$error_mg[] = "Database host can not be empty! Please re-enter.";
		}

		if(empty($database_name)){
			$error_mg[] = "Database name can not be empty! Please re-enter.";
		}

		if(empty($database_username)){
			$error_mg[] = "Database username can not be empty! Please re-enter.";
		}

		if(empty($database_password)){
			$error_mg[] = "Database password can not be empty! Please re-enter.";
		}

		if($error_mg != ''){
			$config_file = file_get_contents($config_file_default);
			$config_file = str_replace("_DB_HOST_", $database_host, $config_file);
			$config_file = str_replace("_DB_NAME_", $database_name, $config_file);
			$config_file = str_replace("_DB_USER_", $database_username, $config_file);
			$config_file = str_replace("_DB_PASSWORD_", $database_password, $config_file);
			
			$link = @mysql_connect($database_host, $database_username, $database_password);
			if($link){					
				if(@mysql_select_db($database_name)){
					$f = @fopen($config_file_path, "w+");
					if(@fwrite($f, $config_file) > 0){
						if(false == ($db_error = apphp_db_install($database_name, $sql_dump))){
							$error_mg[] = "Could not read file ".$sql_dump."! Please check if the file exists.";                            
							@unlink($config_file_path);
						}else{
							// additional operations, like setting up admin passwords etc.
							// ...
							$completed = true;                            
						}						
					}else{				
						$error_mg[] = "Can not open configuration file ".$config_file_directory.$config_file_name;				
					}
					@fclose($f);
				}else{
					$error_mg[] = "Database connecting error! Check your database exists.</span><br/>";
				}
			}else{
				$error_mg[] = "Database connecting error! Check your connection parameters.</span><br/>";
			}			
		}
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Installation Guide</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <meta name="author" content="ApPHP Company - Advanced Power of PHP">
    <meta name="generator" content="ApPHP EasyInstaller">
	<link rel="stylesheet" type="text/css" href="img/styles.css">
</head>
<BODY text=#000000 vLink=#2971c1 aLink=#2971c1 link=#2971c1 bgColor=#ffffff>
<br><br>
<TABLE align="center" width="70%" cellSpacing=0 cellPadding=2 border=0>
<TBODY>
<TR>
    <TD class=text vAlign=top>
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

                        <table class="central" width="100%" cellspacing=0 cellpadding=0 border=0>
                        <tbody>
						<?php if(!$completed){
							foreach($error_mg as $msg){
								echo "<tr><td class=text align=left><span style='color:#bb5500;'>&#8226; ".$msg."</span></td></tr>";
							}
						?>
							</tbody>
							</table>
							<br />

							<table class="text" width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td align='left'>
									<input type="button" class="form_button" value="Back" name="submit" onclick="javascript:history.go(-1);">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" class="form_button" value="Retry" name="submit" onclick="javascript:location.reload();">
								</td>
							</tr>
							</table>

						<?php } else {?>
							<tr>
								<td class=text align=left>
									<b>Step 2. Installation Completed</b>
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<TD class=text align=left>
									The <?php echo $config_file_path;?> file was sucessfully created.
									<br />
									<span style='color:#bb5500;'>
										<b>!!! For security reasons, please remove install/ folder from your server.</b>
									</span>
									<br /><br />
									<?php if($application_start_file != ""){ ?><A href="<?php echo $application_start_file;?>">Proceed to login page</A><?php } ?>
								</td>
							</tr>
							</tbody>
							</table>
							<br />
						<?php } ?>



					</td>
                    <td></td>
                </tr>
                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>
		<br />

		<?php include_once("footer.php"); ?>
    </td>
</tr>
</tbody>
</table>
</body>
</html>
<?php

function apphp_db_install($database, $sql_file) {
    $db_error = false;  
    if(!@apphp_db_select_db($database)){
        if (@apphp_db_query('create database '.$database)){
          apphp_db_select_db($database);
        } else {
          $db_error = mysql_error();
          return false;		
        }
    }
  
    if(!$db_error){
        if(file_exists($sql_file)){
          $fd = fopen($sql_file, 'rb');
          $restore_query = fread($fd, filesize($sql_file));
           fclose($fd);
        } else {
            $db_error = 'SQL file does not exist: ' . $sql_file;
            return false;
        }
        
        $sql_array = array();
        $sql_length = strlen($restore_query);
        $pos = strpos($restore_query, ';');
        for ($i=$pos; $i<$sql_length; $i++) {
            if ($restore_query[0] == '#') {
              $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
              $sql_length = strlen($restore_query);
              $i = strpos($restore_query, ';')-1;
              continue;
            }
            $next = '';
            if($restore_query[($i+1)] == "\n") {
                for ($j=($i+2); $j<$sql_length; $j++) {
                  if (trim($restore_query[$j]) != '') {
                    $next = substr($restore_query, $j, 6);
                    if ($next[0] == '#') {
                      // find out where the break position is so we can remove this line (#comment line)
                      for ($k=$j; $k<$sql_length; $k++) {
                        if ($restore_query[$k] == "\n") break;
                      }
                      $query = substr($restore_query, 0, $i+1);
                      $restore_query = substr($restore_query, $k);
                      // join the query before the comment appeared, with the rest of the dump
                      $restore_query = $query . $restore_query;
                      $sql_length = strlen($restore_query);
                      $i = strpos($restore_query, ';')-1;
                      continue 2;
                    }
                    break;
                  }
                }
                if($next == ''){ // get the last insert query
                    $next = 'insert';
                }
                if((preg_match('/create/i', $next)) || (preg_match('/insert/i', $next)) || (preg_match('/drop t/i', $next))){
                    $next = '';
                    $sql_array[] = substr($restore_query, 0, $i);
                    $restore_query = ltrim(substr($restore_query, $i+1));
                    $sql_length = strlen($restore_query);
                    $i = strpos($restore_query, ';')-1;
                }
            }
        }
  
        for ($i=0; $i<sizeof($sql_array); $i++) {
          apphp_db_query($sql_array[$i]);
        }
        return true;
    }else{
        return false;
    }
}

function apphp_db_select_db($database) {
    return mysql_select_db($database);
}

function apphp_db_query($query) {
    global $link;
    $res=mysql_query($query, $link);
    return $res;
}

/**
 *	Remove bad chars from input
 *	  	@param $str_words - input
 **/
function prepare_input($str_words, $escape = false, $level = 'high'){
    $found = false;
    $str_words = htmlentities(strip_tags($str_words));
    if($level == 'low'){
        $bad_string = array('drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    }else if($level == 'medium'){
        $bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    }else{
        $bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    }
    for($i = 0; $i < count($bad_string); $i++){
        $str_words = str_replace($bad_string[$i], '', $str_words);
    }

    if($escape){
        $str_words = mysql_real_escape_string($str_words);
    }

    return $str_words;
}

?>