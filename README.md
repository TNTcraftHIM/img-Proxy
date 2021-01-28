# img-Proxy
PHP图片反代
在原作者的基础上修了一些Bug和逻辑，然后增加了内置referer功能

## 使用方法:
上传服务器后，访问index.php/?url=请求的图片地址  如果带了ref参数就可以使用指定的referer去请求，如果ref参数为空会首先检查是否有内置指定referer（比如pixiv），没有的话会使用网址自身作为referer
