<?php
/**
 * Purchase List View
 * @see \PurchaseController::index()
 * @var PurchaseDTO[] $purchases
 */
?>
<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 leading-tight">ใบสั่งซื้อ</h1>
            <p class="text-slate-500 mt-1">ติดตามและจัดการยอดสั่งซื้ออะไหล่จากซัพพลายเออร์</p>
        </div>
        <?php render_component('ui/button', [
            'type' => 'link',
            'href' => '/purchase/create',
            'variant' => 'primary',
            'size' => 'md',
            'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'
        ], 'สร้างใบสั่งซื้อ'); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($purchases as $purchase):
            ob_start(); ?>
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center">
                    <div
                        class="bg-indigo-50 p-3 rounded-2xl group-hover:bg-indigo-100 transition-colors border border-indigo-100">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors">PO
                            #<?= htmlspecialchars($purchase->id) ?></h3>
                        <p class="text-xs text-slate-400 font-bold tracking-tight mt-0.5">
                            <?= htmlspecialchars($purchase->buyDate) ?>
                        </p>
                    </div>
                </div>
                <?php
                render_component('ui/badge', [
                    'variant' => 'blue',
                    'class' => 'px-3 py-1 text-[10px]'
                ], 'รอดำเนินการ');
                ?>
            </div>

            <div class="space-y-4 mb-8">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Supplier</p>
                    <p class="text-sm font-bold text-slate-700"><?= htmlspecialchars($purchase->supplierName) ?></p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <?php render_component('ui/button', [
                    'type' => 'link',
                    'href' => '/purchase/' . $purchase->id,
                    'variant' => 'ghost',
                    'size' => 'sm',
                    'class' => 'text-indigo-600 font-black',
                    'icon' => '<svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>'
                ], 'View Details'); ?>
            </div>
            <?php $cardContent = ob_get_clean();
            render_component('ui/card', ['class' => 'hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-50 transition-all group'], $cardContent);
        endforeach; ?>
    </div>

    <?php if (empty($purchases)): ?>
        <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100 mt-8">
            <div class="bg-slate-50 h-20 w-20 rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-4">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <p class="text-slate-400 font-bold">ไม่พบข้อมูลใบสั่งซื้อ</p>
        </div>
    <?php endif; ?>
</div>

</body>

</html>