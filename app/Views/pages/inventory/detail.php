<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex items-center">
        <?php render_component('ui/button', [
            'type' => 'link',
            'href' => '/inventory',
            'variant' => 'ghost',
            'size' => 'sm',
            'class' => 'mr-4 p-2.5',
            'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>'
        ], ''); ?>
        <h1 class="text-2xl font-bold text-slate-900">รายละเอียดอะไหล่ <span class="text-blue-600 ml-2">#<?= htmlspecialchars($item['id']) ?></span></h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-6">
            <?php ob_start(); ?>
                <div class="mb-8">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Product Description</h3>
                    <p class="text-3xl font-black text-slate-900 leading-tight"><?= htmlspecialchars($item['name']) ?></p>
                </div>
                
                <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-100">
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Cost Price</h4>
                        <p class="text-2xl font-black text-slate-600">฿<?= number_format($item['cost'], 2) ?></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Selling Price</h4>
                        <p class="text-2xl font-black text-blue-600 italic">฿<?= number_format($item['price'], 2) ?></p>
                    </div>
                </div>
            <?php $mainInfo = ob_get_clean();
            render_component('ui/card', ['class' => 'p-2'], $mainInfo); ?>
            
            <div class="bg-slate-900 rounded-[2rem] p-8 flex items-center justify-between shadow-2xl shadow-slate-200">
                <div>
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Estimated Profit</h3>
                    <p class="text-3xl font-black text-emerald-400">+ ฿<?= number_format($item['price'] - $item['cost'], 2) ?></p>
                </div>
                <div class="bg-emerald-400/10 p-5 rounded-2xl text-emerald-400 border border-emerald-400/20">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <?php ob_start(); ?>
                <div class="py-4">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 text-center">Current Inventory</h3>
                    <div class="text-7xl font-black text-slate-900 mb-2 text-center tabular-nums"><?= htmlspecialchars($stock) ?></div>
                    <p class="text-xs text-slate-400 font-black uppercase tracking-[0.3em] mb-10 text-center">Units Available</p>
                    
                    <div class="space-y-3">
                        <?php render_component('ui/button', [
                            'variant' => 'slate',
                            'class' => 'w-full py-4 text-base shadow-xl shadow-slate-200',
                            'icon' => '<svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>'
                        ], 'Update Stock'); ?>
                        <?php render_component('ui/button', [
                            'type' => 'link',
                            'href' => '/inventory',
                            'variant' => 'ghost',
                            'class' => 'w-full text-slate-400 font-bold'
                        ], 'Cancel'); ?>
                    </div>
                </div>
            <?php $stockContent = ob_get_clean();
            render_component('ui/card', ['class' => 'ring-2 ring-slate-100'], $stockContent); ?>
        </div>
    </div>
</div>

</body>
</html>
