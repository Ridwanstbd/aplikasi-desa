<form class="mt-8 space-y-6" action="<?php echo e(route('login')); ?>" method="POST" id="loginForm">
    <?php echo csrf_field(); ?>
    <?php if (isset($component)) { $__componentOriginald5660fe61d667043d4f6f0d2cc7d145d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5660fe61d667043d4f6f0d2cc7d145d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Fragments.EmailInput','data' => ['name' => 'email','id' => 'email','label' => 'Email','placeholder' => 'example@example.com','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Fragments.EmailInput'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','id' => 'email','label' => 'Email','placeholder' => 'example@example.com','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5660fe61d667043d4f6f0d2cc7d145d)): ?>
<?php $attributes = $__attributesOriginald5660fe61d667043d4f6f0d2cc7d145d; ?>
<?php unset($__attributesOriginald5660fe61d667043d4f6f0d2cc7d145d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5660fe61d667043d4f6f0d2cc7d145d)): ?>
<?php $component = $__componentOriginald5660fe61d667043d4f6f0d2cc7d145d; ?>
<?php unset($__componentOriginald5660fe61d667043d4f6f0d2cc7d145d); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginala0bf784055eebf1c2183827748e8e818 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala0bf784055eebf1c2183827748e8e818 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Fragments.PasswordInput','data' => ['name' => 'password','id' => 'password','label' => 'Password','placeholder' => 'input password here','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Fragments.PasswordInput'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','id' => 'password','label' => 'Password','placeholder' => 'input password here','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala0bf784055eebf1c2183827748e8e818)): ?>
<?php $attributes = $__attributesOriginala0bf784055eebf1c2183827748e8e818; ?>
<?php unset($__attributesOriginala0bf784055eebf1c2183827748e8e818); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala0bf784055eebf1c2183827748e8e818)): ?>
<?php $component = $__componentOriginala0bf784055eebf1c2183827748e8e818; ?>
<?php unset($__componentOriginala0bf784055eebf1c2183827748e8e818); ?>
<?php endif; ?>

    <div class="flex items-center justify-between">
        <?php if (isset($component)) { $__componentOriginal39866f6df72ca1be7ec9557fd734e971 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal39866f6df72ca1be7ec9557fd734e971 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Checkbox','data' => ['id' => 'remember_me','name' => 'remember','label' => 'Remember Me']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'remember_me','name' => 'remember','label' => 'Remember Me']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal39866f6df72ca1be7ec9557fd734e971)): ?>
<?php $attributes = $__attributesOriginal39866f6df72ca1be7ec9557fd734e971; ?>
<?php unset($__attributesOriginal39866f6df72ca1be7ec9557fd734e971); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal39866f6df72ca1be7ec9557fd734e971)): ?>
<?php $component = $__componentOriginal39866f6df72ca1be7ec9557fd734e971; ?>
<?php unset($__componentOriginal39866f6df72ca1be7ec9557fd734e971); ?>
<?php endif; ?>

        <div class="text-sm">
            <?php if (isset($component)) { $__componentOriginalac7526b587aca80c33c9147a98af94c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac7526b587aca80c33c9147a98af94c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Link','data' => ['href' => ''.e(route('password.request')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('password.request')).'']); ?>
                Forgot your password?
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
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginal272e98d55c438d0d4a19fc6442f4b204 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal272e98d55c438d0d4a19fc6442f4b204 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Form.ButtonSubmit','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Form.ButtonSubmit'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?>
        <h1>Login</h1>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal272e98d55c438d0d4a19fc6442f4b204)): ?>
<?php $attributes = $__attributesOriginal272e98d55c438d0d4a19fc6442f4b204; ?>
<?php unset($__attributesOriginal272e98d55c438d0d4a19fc6442f4b204); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal272e98d55c438d0d4a19fc6442f4b204)): ?>
<?php $component = $__componentOriginal272e98d55c438d0d4a19fc6442f4b204; ?>
<?php unset($__componentOriginal272e98d55c438d0d4a19fc6442f4b204); ?>
<?php endif; ?>
</form><?php /**PATH D:\PENGABDIAN\aplikasi-desa\resources\views/components/Fragments/LoginForm.blade.php ENDPATH**/ ?>