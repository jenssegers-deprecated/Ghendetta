<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section>
    <h1>Attention</h1>
    <p>Do you really want to <?php echo $action ?>?</p>
    <button onclick="javascript:window.location='<?php echo $cancelurl ?>';">Cancel</button>
    <button onclick="javascript:window.location='<?php echo $actionurl ?>';">Confirm</button>
</section>
</body>
</html>
