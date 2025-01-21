<div class="text-center mt-4">
    <p class="text-sm text-gray-600">
        Don't have an account?
        <?php if (isset($component)) { $__componentOriginalac7526b587aca80c33c9147a98af94c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac7526b587aca80c33c9147a98af94c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Link','data' => ['href' => ''.e(route('register')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('register')).'']); ?>
            Register here
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac7526b587aca80c33c9147a98af94c4)): ?>
<?php $attributes = $__attributesOriginalac7526b587aca80c33c9147a98af94c4; ?>
<?php unset($__attributesOriginalac7526b587aca80c33c9147a98af94c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac7526b587aca80c33c9147a98af94c4)): ?>
<?php $component = $__componentOriginalac7526b587aca80c33c9147a98af94c4; ?>
<?php unset($__componentOriginalac7526b587aca80c33c9147a98af94c4); ?>
<?php endif; ?>
    </p>
</div><?php /**PATH D:\PENGABDIAN\aplikasi-desa\resources\views/components/Fragments/RegisterLink.blade.php ENDPATH**/ ?>