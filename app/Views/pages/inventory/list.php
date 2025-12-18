<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">รายการอะไหล่</h1>
            <p class="text-slate-500 mt-1">คลังอัพเดทอะไหล่และอุปกรณ์คงเหลือ</p>
        </div>
        <?php render_component('ui/button', [
            'type' => 'link',
            'href' => '/inventory/create',
            'variant' => 'primary',
            'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'
        ], 'เพิ่มอะไหล่'); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <?php foreach ($inventory as $item): 
            ob_start(); ?>
                <div class="flex justify-between items-start mb-6">
                    <div class="h-12 w-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-blue-50 group-hover:text-blue-500 transition-all">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <?php 
                        $variant = 'emerald';
                        if ($item['quantity'] <= 0) $variant = 'red';
                        elseif ($item['quantity'] < 5) $variant = 'amber';
                        
                        render_component('ui/badge', [
                            'variant' => $variant,
                            'class' => 'text-[10px] font-black uppercase tracking-widest'
                        ], 'Stock: ' . $item['quantity']);
                    ?>
                </div>
                
                <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors mb-1 truncate" title="<?= htmlspecialchars($item['name']) ?>">
                    <?= htmlspecialchars($item['name']) ?>
                </h3>
                <p class="text-xs text-slate-400 font-bold mb-6 tracking-tight">ID: #<?= htmlspecialchars($item['id']) ?></p>
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="bg-slate-50/80 p-3 rounded-xl border border-slate-100">
                        <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-black mb-1">Cost</span>
                        <span class="text-sm font-black text-slate-600">฿<?= number_format($item['cost'], 2) ?></span>
                    </div>
                    <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <span class="block text-[10px] uppercase tracking-widest text-blue-400 font-black mb-1">Sell</span>
                        <span class="text-sm font-black text-blue-600 italic">฿<?= number_format($item['price'], 2) ?></span>
                    </div>
                </div>
                
                <?php render_component('ui/button', [
                    'type' => 'link',
                    'href' => '/inventory/' . $item['id'],
                    'variant' => 'ghost',
                    'size' => 'sm',
                    'class' => 'w-full bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold',
                    'icon' => '<svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>'
                ], 'รายละเอียด'); ?>
            <?php $cardContent = ob_get_clean();
            render_component('ui/card', ['class' => 'hover:shadow-xl hover:shadow-slate-200/50 transition-all group'], $cardContent);
        endforeach; ?>
    </div>
</div>

</body>
</html>
