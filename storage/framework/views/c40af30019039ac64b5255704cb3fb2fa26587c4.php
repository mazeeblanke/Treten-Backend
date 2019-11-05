<?php $__env->startComponent('mail::message'); ?>

Dear Admin,


<?php echo e($message); ?>



Thanks,<br>
<?php echo e($first_name." ".$last_name); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /treten/resources/views/emails/contactus.blade.php ENDPATH**/ ?>