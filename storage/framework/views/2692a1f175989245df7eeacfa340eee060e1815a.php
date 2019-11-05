<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Treten Academy')); ?></title>

    <!-- Scripts -->
    <!-- ÷<script src="<?php echo e(asset('js/app.js')); ?>" defer></script> -->
    <?php echo $__env->yieldContent('mainscript'); ?>
    <!-- <script src="<?php echo e(secure_asset('js/app.js')); ?>" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

   
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i&display=swap" rel="stylesheet"></link>
    <!-- <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet"> -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <!-- <link href="<?php echo e(secure_asset('css/app.css')); ?>" rel="stylesheet"> -->
</head>
<body>
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH /treten/resources/views/layouts/app.blade.php ENDPATH**/ ?>