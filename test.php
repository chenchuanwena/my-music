!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>无标题文档</title>
  <script type="text/javascript" src="jquery.min.js"></script>
  <script src="upload02.js"></script>
  <script type="text/javascript">

    function look() {
      //alert($("form input[type=file]").val())
      alert($("input[name=test]").upload("getFileVal"))
    }
    function clean() {
      $("input[name=test]").upload("clean")
    }
    function ajaxSubmit() {
      $("input[name=test]").upload({
        url: 'index.aspx',
        // 其他表单数据
        params: { name: 'pxblog' },
        // 上传完成后, 返回json, text
        dataType: 'json',
        onSend: function (obj, str) {  return true; },
        // 上传之后回调
        onComplate: function (data) {
          alert(data.file);
        }
      });
      $("input[name=test]").upload("ajaxSubmit")
    }


    function look1() {
      //alert($("form input[type=file]").val())
      alert($("input[name=test1]").upload("getFileVal"))
    }
    function clean1() {
      $("input[name=test1]").upload("clean")
    }
    function ajaxSubmit1() {
      $("input[name=test1]").upload({
        url: 'index.aspx',
        // 其他表单数据
        params: { name: 'pxblog' },
        // 上传完成后, 返回json, text
        dataType: 'json',
        onSend: function (obj, str) { return true; },
        // 上传之后回调
        onComplate: function (data) {
          alert(data.file);
        }
      });
      $("input[name=test1]").upload("ajaxSubmit")
    }
  </script>
</head>

<body>
<p>
  <input type="button" value="look" onclick="look()" />
  <input type="button" value="clean" onclick="clean()" />
  <input type="button" value="ajaxSubmit" onclick="ajaxSubmit()" />
  <input type="file" name="test" />
</p>
<p>
  <input type="button" value="look1" onclick="look1()" />
  <input type="button" value="clean1" onclick="clean1()" />
  <input type="button" value="ajaxSubmit1" onclick="ajaxSubmit1()" />
  <input type="file" name="test1" />
</p>
</body>
</html>