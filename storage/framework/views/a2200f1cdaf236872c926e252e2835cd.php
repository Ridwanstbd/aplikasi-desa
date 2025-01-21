<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'password',
    'id' => 'password',
    'label' => 'Password',
    'placeholder' => 'Password'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => 'password',
    'id' => 'password',
    'label' => 'Password',
    'placeholder' => 'Password'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div>
    <?php if (isset($component)) { $__componentOriginalde1ed3c746d26515f531880cf9a239b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalde1ed3c746d26515f531880cf9a239b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Label','data' => ['for' => ''.e($id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => ''.e($id).'']); ?>
        <?php echo e($label); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalde1ed3c746d26515f531880cf9a239b9)): ?>
<?php $attributes = $__attributesOriginalde1ed3c746d26515f531880cf9a239b9; ?>
<?php unset($__attributesOriginalde1ed3c746d26515f531880cf9a239b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalde1ed3c746d26515f531880cf9a239b9)): ?>
<?php $component = $__componentOriginalde1ed3c746d26515f531880cf9a239b9; ?>
<?php unset($__componentOriginalde1ed3c746d26515f531880cf9a239b9); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal49ba1b4003b4fc902081c96f9ea06d06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49ba1b4003b4fc902081c96f9ea06d06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Form.Input','data' => ['type' => 'password','id' => $id,'name' => $name,'placeholder' => $placeholder,'attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Form.Input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'password','id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($id),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholder),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal49ba1b4003b4fc902081c96f9ea06d06)): ?>
<?php $attributes = $__attributesOriginal49ba1b4003b4fc902081c96f9ea06d06; ?>
<?php unset($__attributesOriginal49ba1b4003b4fc902081c96f9ea06d06); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal49ba1b4003b4fc902081c96f9ea06d06)): ?>
<?php $component = $__componentOriginal49ba1b4003b4fc902081c96f9ea06d06; ?>
<?php unset($__componentOriginal49ba1b4003b4fc902081c96f9ea06d06); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal9af86b919e0a8185289415ca81fdb837 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9af86b919e0a8185289415ca81fdb837 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.Elements.Form.InputError','data' => ['message' => $errors->first($name)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('Elements.Form.InputError'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first($name))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9af86b919e0a8185289415ca81fdb837)): ?>
<?php $attributes = $__attributesOriginal9af86b919e0a8185289415ca81fdb837; ?>
<?php unset($__attributesOriginal9af86b919e0a8185289415ca81fdb837); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9af86b919e0a8185289415ca81fdb837)): ?>
<?php $component = $__componentOriginal9af86b919e0a8185289415ca81fdb837; ?>
<?php unset($__componentOriginal9af86b919e0a8185289415ca81fdb837); ?>
<?php endif; ?>
</div><?php /**PATH D:\PENGABDIAN\aplikasi-desa\resources\views/components/Fragments/PasswordInput.blade.php ENDPATH**/ ?>