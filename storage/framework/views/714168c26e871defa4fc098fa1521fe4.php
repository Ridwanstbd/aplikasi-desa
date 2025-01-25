<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <link rel="icon" type="image/png" >
    
    <title>Home</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="font-sans text-gray-900 antialiased">
    <main class="container">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module">
        function showAlert(icon, title, text){
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonText: 'Ok'
            });
        }
        <?php if(session('success')): ?>
            showAlert('success','Sukses!','<?php echo e(session('success')); ?>')
        <?php endif; ?>
        <?php if(session('error')): ?>
            <?php $errorMessages = session('error'); ?>
            <?php if(is_array($errorMessages)): ?>
                <?php $__currentLoopData = $errorMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorMessage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    showAlert('error','Oops...','<?php echo e($errorMessage); ?>')
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                    showAlert('error','Oops...','<?php echo e($errorMessages); ?>')
            <?php endif; ?>
        <?php endif; ?>
    </script>
    <!-- sweetalert2 -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\PENGABDIAN\aplikasi-desa\resources\views/layouts/guest.blade.php ENDPATH**/ ?>