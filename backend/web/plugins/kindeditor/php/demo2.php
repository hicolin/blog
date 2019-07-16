<?php
$htmlData = '';
if (!empty($_POST['content1'])) {
    if (get_magic_quotes_gpc()) {
        $htmlData = stripslashes($_POST['content1']);
    } else {
        $htmlData = $_POST['content1'];
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../plugins/code/highlight.min.css">
    <script charset="utf-8" src="../plugins/code/highlight.min.js"></script>

</head>
<body>
<?php echo $htmlData; ?>

<script>
    window.onload = function () {
        hljs.initHighlighting();
    }
</script>
</body>
</html>


