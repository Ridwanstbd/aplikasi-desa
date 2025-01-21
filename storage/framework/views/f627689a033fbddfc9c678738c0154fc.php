<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id','name','label']));

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

foreach (array_filter((['id','name','label']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="flex items-center">
    <input id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" type="checkbox"
    <?php echo e($attributes->merge(['class'=>"h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"])); ?> 
           >
    <label for="<?php echo e($id); ?>" class="ml-2 block text-sm text-gray-900">
        <?php echo e($label); ?>

    </label>
</div><?php /**PATH D:\PENGABDIAN\aplikasi-desa\resources\views/components/Elements/Checkbox.blade.php ENDPATH**/ ?>