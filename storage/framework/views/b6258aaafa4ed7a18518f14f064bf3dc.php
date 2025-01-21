<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title']));

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

foreach (array_filter((['title']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
    <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            <?php echo e($title); ?>

        </h2>
    </div>
    <?php echo e($slot); ?>

</div><?php /**PATH D:\PENGABDIAN\aplikasi-desa\resources\views/components/Fragments/FormContainer.blade.php ENDPATH**/ ?>