<?php
/**
 * UI Badge Component
 * @param array $props {
 *   variant: string (blue|emerald|indigo|amber|red|slate),
 *   class: string
 * }
 */
$variants = [
    'blue' => 'bg-blue-100 text-blue-800 border-blue-200 shadow-blue-50',
    'emerald' => 'bg-emerald-100 text-emerald-800 border-emerald-200 shadow-emerald-50',
    'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200 shadow-indigo-50',
    'amber' => 'bg-amber-100 text-amber-800 border-amber-200 shadow-amber-50',
    'red' => 'bg-red-100 text-red-800 border-red-200 shadow-red-500',
    'slate' => 'bg-slate-100 text-slate-800 border-slate-200 shadow-slate-50',
];

$variantClass = $variants[$props['variant'] ?? 'blue'] ?? $variants['blue'];
?>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border shadow-sm <?= $variantClass ?> <?= $props['class'] ?? '' ?>">
    <?= $content ?>
</span>
