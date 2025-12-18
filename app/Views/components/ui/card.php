<?php
/**
 * UI Card Component
 * @param array $props {
 *   title: string,
 *   subtitle: string,
 *   header_actions: string (HTML),
 *   footer: string (HTML),
 *   class: string,
 *   body_class: string
 * }
 */
?>
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden <?= $props['class'] ?? '' ?>">
    <?php if (isset($props['title']) || isset($props['header_actions'])): ?>
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <?php if (isset($props['title'])): ?>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider"><?= $props['title'] ?></h3>
                <?php endif; ?>
                <?php if (isset($props['subtitle'])): ?>
                    <p class="text-xs text-slate-500 mt-0.5"><?= $props['subtitle'] ?></p>
                <?php endif; ?>
            </div>
            <?php if (isset($props['header_actions'])): ?>
                <div class="flex items-center space-x-2">
                    <?= $props['header_actions'] ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="p-6 <?= $props['body_class'] ?? '' ?>">
        <?= $content ?>
    </div>

    <?php if (isset($props['footer'])): ?>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            <?= $props['footer'] ?>
        </div>
    <?php endif; ?>
</div>
