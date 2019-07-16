<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>KindEditor PHP</title>
	<script charset="utf-8" src="../kindeditor-all.js"></script>
	<script charset="utf-8" src="../lang/zh-CN.js"></script>
	<script>
        KindEditor.ready(function(K) {
		    // var option = {
            //     // cssPath : '../plugins/code/prettify.css',
            //     uploadJson : '../php/upload_json.php',
            //     fileManagerJson : '../php/file_manager_json.php',
            //     allowFileManager : true,
            //     afterCreate : function() {
            //         var self = this;
            //         K.ctrl(document, 13, function() {
            //             self.sync();
            //             K('form[name=example]')[0].submit();
            //         });
            //         K.ctrl(self.edit.doc, 13, function() {
            //             self.sync();
            //             K('form[name=example]')[0].submit();
            //         });
            //     }
            // };
			var editor1 = K.create('textarea[name="content1"]');
            // prettyPrint();
        });
    </script>
</head>
<body>
	<form name="example" method="post" action="demo2.php">
		<textarea name="content1" style="width:700px;height:200px;visibility:hidden;"></textarea>
		<br />
		<input type="submit" name="button" value="提交内容" /> (提交快捷键: Ctrl + Enter)
	</form>
</body>
</html>

