<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="https://github.com/lecepin">
  <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.0-beta2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.0-beta2/js/bootstrap.min.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

  <title>无限上传</title>
</head>

<body style="padding: 15px;">
  <?php
  $ip = get_local_ip();

  if ($_SERVER['SERVER_NAME'] != $ip) {
    $cli = 'php -c ./ -S ' . $ip . ':' . $_SERVER['SERVER_PORT'];
    $addr = 'http://' . $ip . ':' . $_SERVER['SERVER_PORT'];
    echo '<div class="alert alert-danger" role="alert">
            当前 IP 无法使用，请重新执行如下命令： <strong>' . $cli . '</strong>
            <button type="button" class="btn btn-secondary btn-sm copy-cli" data-clipboard-text="' . $cli . '">复制</button>
          </div>
          <small>重新执行命令后，访问如下地址：<a href="' . $addr . '">' . $addr . '</a></small>
          ';
  } else {
  ?>
    <form class="row" action="./" method="post" enctype="multipart/form-data" id="form">
      <div class="col-auto">
        <input name="files[]" class="form-control" type="file" multiple>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary" id="submit-btn">上传文件</button>
        <button type="button" class="btn btn-link" onclick="javascript:window.location.href='list.php'">已上传</button>
      </div>
    </form>

    <div id="qrcode" style="margin: 15px 0;"></div>

    <button type="button" class="btn btn-success" onclick="javascript:window.location.href='new/'">
      JQ 版 <span class="badge rounded-pill bg-warning">New</span>
    </button>
  <?php
  }

  $files = $_FILES['files'];
  if ($files) echo '<div class="toast-container position-absolute bottom-0 end-0 p-3">';
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
      echo '<div class=" toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="d-flex">
                    <div class="toast-body">' . $res . '：上传成功</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                </div>';
    } else {
      echo '<div class=" toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                  <div class="toast-body">' . $error . '</div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
              </div>';
    }
  }
  if ($files) echo '</div>';

  ?>

  <script>
    new ClipboardJS('.copy-cli');

    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
      toastEl.addEventListener('hidden.bs.toast', function() {
        window.location.replace('./');
      })
      return (new bootstrap.Toast(toastEl, {
        delay: 2000
      })).show()
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

<?php
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
  exec("ipconfig", $out, $stats);

  if (!empty($out)) {
    for ($i = 0; $i < count($out); $i++) {
      $startPos = strpos($out[$i], "IPv4 ");

      if ($startPos) {
        $startPos = strpos($out[$i], ": ");
        return substr($out[$i], $startPos + 2, strlen($out[$i]) - ($startPos + 2));
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
