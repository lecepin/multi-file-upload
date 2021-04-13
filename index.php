<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.0-beta2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.0-beta2/js/bootstrap.min.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <title></title>
</head>

<body style="padding: 15px;">
  <?php
  $ip = get_local_ip();

  if ($_SERVER['SERVER_NAME'] != $ip) {
    echo '<div class="alert alert-danger" role="alert">
            当前 IP 无法使用，请重新执行如下命令： <strong>php -c ./ -S ' . $ip . ':' . $_SERVER['SERVER_PORT'] . '</strong>
          </div>';
  }
  ?>
  <form class="row" action="./?upload=true" method="post" enctype="multipart/form-data" id="form">
    <div class="col-auto">
      <input name="files[]" class="form-control" type="file" multiple>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary" id="submit-btn">上传文件</button>
    </div>
  </form>

  <div id="qrcode" style="margin-top: 15px;"></div>

  <?php
  if ($_GET['upload'] == 'true') {
    $files = $_FILES['files'];
    for ($i = 0, $len = count($files['name']); $i < $len; $i++) {
      $file = array(
        'name' => $files['name'][$i],
        'type' => $files['type'][$i],
        'tmp_name' => $files['tmp_name'][$i],
        'error' => $files['error'][$i],
        'size' => $files['size'][$i]
      );
      $res = fileUpload($file, './Upload/', $error);
      if ($res) {
        echo '<div class="position-absolute toast align-items-center text-white bg-success border-0 bottom-0 end-0" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="d-flex">
                    <div class="toast-body">上传成功</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                </div>';
      } else {
        echo '<div class="position-absolute toast align-items-center text-white bg-danger border-0 bottom-0 end-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                  <div class="toast-body">' . $error . '</div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
              </div>';
      }
    }
  }

  function fileUpload($file, $path, &$error)
  {
    if (!isset($file['error'])) {
      $error = '文件无效';
      return false;
    }
    if (!is_dir($path)) {
      mkdir($path);
    }

    switch ($file['error']) {
      case 1:
      case 2:
        $error = '文件超过服务器允许大小';
        return false;
      case 3:
        $error = '文件只有部分上传';
        return false;
      case 4:
        $error = '用户没有选择文件上传';
        return false;
      case 6:
      case 7:
        $error = '服务器操作失败';
        return false;
    }

    if (@move_uploaded_file($file['tmp_name'], $path . '' . $file['name'])) {
      return $file['name'];
    } else {
      $error = '文件上传失败';
      return false;
    }
  }


  function get_local_ip()
  {
    $preg = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";

    exec("ipconfig", $out, $stats);

    if (!empty($out)) {
      for ($i = 0; $i < count($out); $i++) {
        $startPos = strpos($out[$i], "inet ");

        if ($startPos && !strpos($out[$i], "inet 127")) {
          return substr($out[$i], $startPos + 5, strpos($out[$i], " netmask") - ($startPos + 5));
        }
      }
    }

    exec("ifconfig", $out, $stats);
    if (!empty($out)) {

      for ($i = 0; $i < count($out); $i++) {
        $startPos = strpos($out[$i], "inet ");

        if ($startPos && !strpos($out[$i], "inet 127")) {
          return substr($out[$i], $startPos + 5, strpos($out[$i], " netmask") - ($startPos + 5));
        }
      }
    }
    return '127.0.0.1';
  }
   
  ?>

  <script>
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
      return (new bootstrap.Toast(toastEl, {})).show()
    })

    document.getElementById('submit-btn').onclick = e => {
      e.target.disabled = true;
      e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 上传中……';
      document.getElementById('form').submit();
    }

    <?php
    if ($ip == $_SERVER['SERVER_NAME'])
      echo 'new QRCode(document.getElementById("qrcode"), {
            text: "http://' . $ip . ':' . $_SERVER['SERVER_PORT'] . '",
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
          });'
    ?>
  </script>
</body>

</html>