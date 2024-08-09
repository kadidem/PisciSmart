

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent("title"); ?> - <?php echo e(config("app.name")); ?></title>

    <link rel="stylesheet" href="<?php echo e(asset("css/themify-icons.css")); ?>">
    <link rel="stylesheet" href="<?php echo e(asset("css/feather.css")); ?>">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo e(asset("css/style.css")); ?>"> 



</head>

<body class="color-theme-blue">

 <?php echo $__env->yieldContent("content"); ?>



    <script src="<?php echo e(asset("js/plugin.js")); ?>"></script>
    <script src="<?php echo e(asset("js/scripts.js")); ?>"></script>
    
</body>

</html><?php /**PATH /home/ifiboys/ProjetGithub/PisciSmart/pisci_smart/resources/views/layouts/guest.blade.php ENDPATH**/ ?>