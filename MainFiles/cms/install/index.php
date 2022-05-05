<?php

//error_reporting(E_NONE); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../application/config/database.php';

// Only load the classes in case the user submitted the form
if($_POST) {

	// Load the classes and create the new objects
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();


	// Validate the post data
	if($core->validate_post($_POST) == true)
	{

		// First create the database, then create tables, then write config file
		if($database->create_database($_POST) == false) {
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} else if ($database->create_tables($_POST) == false) {
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} else if ($core->write_config($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/database.php file to 777");
		}

		// If no errors, redirect to registration page
		if(!isset($message)) {
			$redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
			$redir .= "://".$_SERVER['HTTP_HOST'];
			$redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
			$redir = str_replace('install/','',$redir); 
			header( 'Location: ' . $redir . 'admin/dashboard' ) ;
		}

	}
	else {
		$message = $core->show_message('error','Not all fields have been filled in correctly. The host, username, password, and database name are required.');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="">
	<title>Install | DroidMarketCMS</title>
	<link rel="stylesheet" type="text/css" media="screen" href="../statics/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="../statics/bootstrap/css/bootstrap-responsive.min.css"/>
	<style type="text/css">
	.error {
		background: #ffd1d1;
		border: 1px solid #ff5858;
		padding: 4px;
	}
	.wrapper{
		width: 750px;
		margin: 0px auto;
		display: block;
		position: relative;
		overflow: hidden;
	}

	input{
		width: 400px;
		height: 45px;
	}

	legend{
		border: none;
	}

	.page-header h3{
		font-weight: normal;
		font-size: 45px;
	}
	</style>
</head>
<body>
	<div class="page-header" style="text-align:center">
		<h3><b>DroidMarketCMS</b> Install Wizard</h3>
	</div>
	<div class="wrapper">
		<?php if(is_writable($db_config_path)){?>

		<?php if(isset($message)) {echo '<p class="error">' . $message . '</p>';}?>

		<form id="install_form" class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset>
				<legend style="text-align:center">Database settings</legend>
				<div class="form-group">
					<label class="control-label col-xs-2">Host Name</label>
					<div class="controls col-xs-10">
						<input type="text" id="hostname" name="hostname" value="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-2">Username</label>
					<div class="controls col-xs-10">
						<input type="text" id="username" name="username" value="">
					</div>
				</div>


				<div class="form-group">
					<label class="control-label col-xs-2">Password</label>
					<div class="controls col-xs-10">
						<input type="password" id="password" name="password" value="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-2">DatabaseName</label>
					<div class="controls col-xs-10">
						<input type="text" id="database" name="database" value="">
					</div>
				</div>

				<div class="form-group">
					<div class="col-xs-10 col-xs-offset-2">
						<input type="submit" value="Install" id="submit" class="btn btn-primary">
					</div>
				</div>
			</fieldset>
		</form>

		<?php } else { ?>
		<p class="error">Please make the application/config/database.php file writable. <strong>Example</strong>:<br /><br /><code>chmod 777 application/config/database.php</code></p>
		<?php } ?>
	</div>
</body>
</html>