<?php
if (isset($_POST['submit'])) {
  $_SESSION['sticky'] = $_POST;
  $mysqli = @new mysqli($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
  if ($mysqli->connect_error) {
    echo '<p style="color:red;">Sorry but the database information that you entered did not work, please try again.</p>';
  } else {
    $mysqli->close();
    $file = @file_get_contents(ROOT . '/lib/config/config.php');
    foreach ($_POST as $key => $val)
      if ($key != 'submit')
        $file = preg_replace('/\'' . strtoupper($key) . '\', \'.*\'/', '\'' . strtoupper($key) . '\', \'' . htmlentities($val,ENT_QUOTES,'UTF-8') . '\'', $file);
    $fp = fopen(ROOT . '/lib/config/config.php', 'w');
    fwrite($fp, $file);
    unset($_SESSION['sticky']);
    header('Location: /');
  }
}
?>
<!doctype html>
<html>
  <head>
    <title>Nibble framework installation</title>
    <style type="text/css">
      label, input{display:block;width:100%;}
      input[type="submit"]{width:auto;}
    </style>
  </head>
  <body>
    <div style="padding:10px;margin:auto;width:720px;">
      <h1>Welcome to the Nibble framework installer</h1>
      <p>
        Below is a form that requires the information to install the Nibble
        framework on this server.  Please fill out the information and click the
        "Install Nibble on my server" button to continue installing the Nibble
        framework.
      </p>
      <form action="/" method="post">
        <label for="site_name">Site name/title:</label>
        <input type="text" name="site_name" id="site_name" value="<?php echo Useful::stickyText('site_name') ?>" />
        <label for="email">Administrators email address:</label>
        <input type="text" name="email" id="email" value="<?php echo Useful::stickyText('email') ?>" />
        <label for="dbhost">Database host name</label>
        <input type="text" name="dbhost" id="dbhost" value="<?php echo Useful::stickyText('dbhost') ?>" />
        <label for="dbname">Database name</label>
        <input type="text" name="dbname" id="dbname" value="<?php echo Useful::stickyText('dbname') ?>" />
        <label for="dbuser">Database username</label>
        <input type="text" name="dbuser" id="dbuser" value="<?php echo Useful::stickyText('dbuser') ?>" />
        <label for="dbpass">Database password</label>
        <input type="text" name="dbpass" id="dbpass" value="<?php echo Useful::stickyText('dbpass') ?>" />
        <input type="submit" value="Install Nibble on my server" name="submit" />
      </form>
    </div>
  </body>
</html>
