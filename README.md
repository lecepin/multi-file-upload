# 无限上传工具

为了更方便的解决 手机到电脑、电脑到手机的上传和下载能力，特别用 PHP 写了一个。

相比于其他的解决方案，它有如下优势：

- 类比 NodeJS 版本，不用再去安装各种依赖（国内安装依赖还是比较慢的）。
- 大多系统都支持 `php` 命令，无需再去安装环境（重新安装环境也是很方便的）。
- 支持无限文件、无限大小上传。
- 避免了原来传输数据需要电脑和手机都安装软件（如微信、钉钉）的问题了。
- 局域网数据直连，避免了中间存储。

### 使用

如下方式使用：

```
php -c ./ -S 地址:端口
```

如：

```
php -c ./ -S localhost:8000
```

输出：

```
$ php -c ./ -S localhost:8000
PHP 7.3.24-(to be removed in future macOS) Development Server started at Wed Apr 14 13:49:57 2021
Listening on http://localhost:8000
Document root is /Users/lecepin/multi-file-upload
Press Ctrl-C to quit.
```

点击 `Listening on` 后面的地址 `http://localhost:8000` 即可访问。

> 注意：因为手机访问需要用的电脑中的局域网地址，所以访问上面命令生成后的地址，会对 IP 进行校验，如果不是局域网的，则会生成一个准确的命令，再执行一次即可，如下图所示：

<img width="500" src="https://img.alicdn.com/imgextra/i1/O1CN01OHt05l25pOs2uR1Ir_!!6000000007575-2-tps-1406-716.png" />



### 上传

正常启动后，界面如下所示：

<img width="500" src="https://img.alicdn.com/imgextra/i2/O1CN01HGlJuN1GsZCoF9fdy_!!6000000000678-2-tps-1398-652.png" />

> 手机和电脑在同一网络的情况下，直接扫码即可访问。

上传后，会进行相应状态提示，如下图所示：

<img width="500" src="https://img.alicdn.com/imgextra/i2/O1CN013TZTh61MfdijwWlvt_!!6000000001462-2-tps-1394-762.png" />

### 下载

如果想进行上传文件的下载，可以点击 `已上传` 链接进行访问，如下图所示：

<img width="500" src="https://img.alicdn.com/imgextra/i3/O1CN01qHvhiG21www7Zbcni_!!6000000007050-2-tps-1414-588.png" />

对于需要提供给手机端进行下载的文件，也可以直接放到 `Upload` 目录中，会在 `已下载` 页面呈现。

> `Upload` 文件夹会在上传文件操作中自动创建。