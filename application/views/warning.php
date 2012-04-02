<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section>
    <h1>Attention</h1>
    <p>Do you really want to <?php echo $action ?>?</p>
    <a href="<?php echo $cancel_url ?>" class="btn">Cancel</a>
    <a href="<?php echo $action_url ?>" class="btn">Confirm</a>
</section>
</body>
</html>
