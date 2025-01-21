<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <?php if (isset($component)) { $__componentOriginala297ce39b107f3da84bbb381706b302a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala297ce39b107f3da84bbb381706b302a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Head','data' => ['title' => $title ?? 'Login']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title ?? 'Login')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala297ce39b107f3da84bbb381706b302a)): ?>
<?php $attributes = $__attributesOriginala297ce39b107f3da84bbb381706b302a; ?>
<?php unset($__attributesOriginala297ce39b107f3da84bbb381706b302a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala297ce39b107f3da84bbb381706b302a)): ?>
<?php $component = $__componentOriginala297ce39b107f3da84bbb381706b302a; ?>
<?php unset($__componentOriginala297ce39b107f3da84bbb381706b302a); ?>
<?php endif; ?>

<body class="bg-gray-200">
    <div class="min-h-screen flex items-center justify-center">
        <?php echo e($slot); ?>

    </div>
</body>
</html><?php /**PATH D:\PENGABDIAN\aplikasi-desa\resources\views/components/Layouts/AuthLayout.blade.php ENDPATH**/ ?>