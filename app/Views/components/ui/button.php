<?php
/**
 * UI Button Component
 * @param array $props {
 *   type: string (button|submit|link),
 *   href: string,
 *   variant: string (primary|secondary|success|danger|ghost),
 *   size: string (sm|md|lg),
 *   class: string,
 *   icon: string (HTML)
 * }
 */
$variants = [
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-100',
    'secondary' => 'bg-slate-800 hover:bg-slate-900 text-white shadow-slate-100',
    'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-100',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-red-100',
    'ghost' => 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$variantClass = $variants[$props['variant'] ?? 'primary'] ?? $variants['primary'];
$sizeClass = $sizes[$props['size'] ?? 'md'] ?? $sizes['md'];
$baseClass = "inline-flex items-center justify-center font-bold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-offset-2 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none";
$class = "{$baseClass} {$variantClass} {$sizeClass} " . ($props['class'] ?? '');

if (($props['type'] ?? 'button') === 'link' && isset($props['href'])): ?>
    <a href="<?= $props['href'] ?>" class="<?= $class ?>">
        <?php if (isset($props['icon'])): ?>
            <span class="<?= isset($content) && !empty(trim($content)) ? 'mr-2' : '' ?>"><?= $props['icon'] ?></span>
        <?php endif; ?>
        <?= $content ?>
    </a>
<?php else: ?>
    <button type="<?= $props['type'] ?? 'button' ?>" class="<?= $class ?>">
        <?php if (isset($props['icon'])): ?>
            <span class="<?= isset($content) && !empty(trim($content)) ? 'mr-2' : '' ?>"><?= $props['icon'] ?></span>
        <?php endif; ?>
        <?= $content ?>
    </button>
<?php endif; ?>
