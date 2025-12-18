<?php
/**
 * Purchase Add View
 * @see \PurchaseController::create()
 * @see \PurchaseController::store()
 * @var SupplierDTO[] $suppliers
 * @var string|null $error
 */
?>
<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10 flex items-center">
        <?php render_component('ui/button', [
            'type' => 'link',
            'href' => '/purchase',
            'variant' => 'ghost',
            'size' => 'sm',
            'class' => 'mr-4 p-2.5',
            'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>'
        ], ''); ?>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">สร้างใบสั่งซื้อใหม่</h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="mb-8 bg-red-50 border-l-4 border-red-400 p-6 rounded-r-[2rem] shadow-sm shadow-red-100/50">
            <div class="flex">
                <div class="flex-shrink-0 text-red-500">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-black text-red-700 leading-tight"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php ob_start(); ?>
    <form action="/purchase" method="POST" class="space-y-8">
        <div class="grid grid-cols-1 gap-8">
            <div>
                <label for="supplier_id"
                    class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Supplier
                    Source</label>
                <select name="supplier_id" id="supplier_id" required
                    class="block w-full border-slate-200 rounded-[1.5rem] focus:ring-blue-500 focus:border-blue-500 p-4 bg-slate-50 hover:bg-white transition-all text-base font-black text-slate-900">
                    <option value="">เลือกจากรายชื่อซัพพลายเออร์</option>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= $supplier->id ?>"><?= htmlspecialchars($supplier->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="purchase_date"
                        class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Order
                        Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" required value="<?= date('Y-m-d') ?>"
                        class="block w-full border-slate-200 rounded-[1.5rem] focus:ring-blue-500 focus:border-blue-500 p-4 bg-slate-50 font-black text-slate-900">
                </div>
                <div>
                    <label for="due_date"
                        class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Payment
                        Due</label>
                    <input type="date" name="due_date" id="due_date" required
                        value="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                        class="block w-full border-slate-200 rounded-[1.5rem] focus:ring-blue-500 focus:border-blue-500 p-4 bg-slate-50 font-black text-slate-900">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-8 border-t border-slate-100">
            <?php render_component('ui/button', [
                'type' => 'submit',
                'variant' => 'primary',
                'size' => 'lg',
                'class' => 'px-12 py-5 shadow-2xl shadow-blue-200 ring-4 ring-blue-50/50',
                'icon' => '<svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>'
            ], 'สร้างใบสั่งซื้อและดำเนินการต่อ'); ?>
        </div>
    </form>
    <?php $formContent = ob_get_clean();
    render_component('ui/card', ['class' => 'p-2 shadow-2xl shadow-slate-200/50 ring-4 ring-slate-50/50'], $formContent); ?>
</div>

</body>

</html>