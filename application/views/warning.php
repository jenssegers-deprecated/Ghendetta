<?php include_once('head.tpl'); ?>
<body>
<?php include_once('navigation.tpl'); ?>

<section class="container v-warning" role="main">
    <h1>Attention</h1>
    <p><?php echo $message ?></p>
    <a href="<?php echo $cancel_url ?>" class="btn">Cancel</a>
    <a href="<?php echo $action_url ?>" class="btn">Confirm</a>
</section>

<?php include_once('footer.tpl'); ?>

</body>
</html>
