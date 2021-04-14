<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="https://github.com/lecepin">
  <title>已上传文件</title>
</head>

<body>
  <h2>已上传文件</h2>
  <a href="./">
    <- 返回 </a>
      <?php
      function get_allfiles($path, &$files)
      {
        if (is_dir($path)) {
          $dp = dir($path);
          while ($file = $dp->read()) {
            if ($file != "." && $file != "..") {
              get_allfiles($path . "/" . $file, $files);
            }
          }
          $dp->close();
        }
        if (is_file($path)) {
          $files[] =  $path;
        }
      }

      function get_filenamesbydir($dir)
      {
        $files =  array();
        if (is_dir($dir)) {
          $dp = dir($dir);
          while ($file = $dp->read()) {
            if ($file != "." && $file != "..") {
              get_allfiles($dir . "/" . $file, $files);
            }
          }
          $dp->close();
        }
        if (is_file($dir)) {
          $files[] =  $dir;
        }
        return $files;
      }

      $dir = 'Upload';
      $filenames = get_filenamesbydir($dir);
      echo '<ul>';
      foreach ($filenames as $value) {
        echo  '<li><a href="/' . $value . '">' . substr($value, strlen($dir) + 1) . '</a></li>';
      }

      echo '</ul>';
      ?>

</body>

</html>