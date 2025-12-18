<?php
/**
 * Purchase Detail View
 * @see \PurchaseController::show()
 * @var PurchaseDetailDTO $data
 */
?>
<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center">
            <?php render_component('ui/button', [
                'type' => 'link',
                'href' => '/purchase',
                'variant' => 'ghost',
                'size' => 'sm',
                'class' => 'mr-4 p-2.5',
                'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>'
            ], ''); ?>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">ใบสั่งซื้อ <span
                        class="text-blue-600 ml-2">#<?= htmlspecialchars($data->id) ?></span></h1>
                <p class="text-slate-500 font-medium">จัดการสถานะและการรับสินค้า</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php
            render_component('ui/badge', [
                'variant' => 'blue',
                'class' => 'px-6 py-2 text-sm font-black ring-4 ring-blue-50'
            ], htmlspecialchars($data->status));

            if ($data->isPaid) {
                render_component('ui/badge', [
                    'variant' => 'emerald',
                    'class' => 'px-6 py-2 text-sm font-black ring-4 ring-emerald-50'
                ], 'ชำระเงินแล้ว');
            }

            if ($data->isReceived) {
                render_component('ui/badge', [
                    'variant' => 'indigo',
                    'class' => 'px-6 py-2 text-sm font-black ring-4 ring-indigo-50'
                ], 'รับของแล้ว');
            }
            ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Details Table -->
            <?php ob_start(); ?>
            <div class="p-0">
                <?php if (!empty($data->items)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400">
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">รายการ / คำอธิบาย
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-center">จำนวน
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-right">
                                        ราคา/หน่วย
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-right">รวม
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($data->items as $item): ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-5">
                                            <p class="text-sm font-black text-slate-800"><?= htmlspecialchars($item->name) ?>
                                            </p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">Part ID:
                                                #<?= htmlspecialchars($item->inventoryId ?? 'N/A') ?></p>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span
                                                class="inline-flex px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-600"><?= number_format($item->amount) ?></span>
                                        </td>
                                        <td class="px-6 py-5 text-right font-bold text-slate-600 text-sm">
                                            ฿<?= number_format($item->cost, 2) ?></td>
                                        <td class="px-6 py-5 text-right font-black text-slate-900 text-base italic">
                                            ฿<?= number_format($item->totalCost, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-slate-900 text-white">
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-8 text-right text-xs font-black uppercase tracking-[0.2em] opacity-50">
                                        Total Construction Cost</td>
                                    <td class="px-6 py-8 text-right font-black text-3xl italic text-blue-400">
                                        ฿<?= number_format($data->totalCost, 2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="py-20 text-center">
                        <div
                            class="bg-slate-50 h-20 w-20 rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-4">
                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">ยังไม่มีรายการสินค้า</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php $detailsContent = ob_get_clean();
            render_component('ui/card', ['title' => 'รายการสินค้าในใบสั่งซื้อ'], $detailsContent); ?>

            <!-- Manage Actions -->
            <?php if ($data->status === 'รอดำเนินการ' || !$data->isPaid || !$data->isReceived): ?>
                <?php ob_start(); ?>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <?php if ($data->status === 'รอดำเนินการ'): ?>
                        <?php render_component('ui/button', [
                            'type' => 'link',
                            'href' => '/purchase/' . $data->id . '/activate',
                            'variant' => 'primary',
                            'size' => 'lg',
                            'class' => 'shadow-xl shadow-blue-100'
                        ], 'ยืนยันใบสั่งซื้อ'); ?>
                    <?php endif; ?>

                    <?php if (!$data->isPaid): ?>
                        <?php render_component('ui/button', [
                            'type' => 'link',
                            'href' => '/purchase/' . $data->id . '/pay',
                            'variant' => 'success',
                            'size' => 'lg',
                            'class' => 'shadow-xl shadow-emerald-100'
                        ], 'แจ้งชำระเงิน'); ?>
                    <?php endif; ?>

                    <?php if (!$data->isReceived): ?>
                        <?php render_component('ui/button', [
                            'type' => 'link',
                            'href' => '/purchase/' . $data->id . '/receive',
                            'variant' => 'slate',
                            'size' => 'lg',
                            'class' => 'shadow-xl shadow-slate-200'
                        ], 'ยืนยันรับของ'); ?>
                    <?php endif; ?>
                </div>
                <?php $actionsContent = ob_get_clean();
                render_component('ui/card', ['title' => 'ดำเนินการเปลี่ยนสถานะ'], $actionsContent); ?>
            <?php endif; ?>
        </div>

        <div class="space-y-8">
            <!-- Summary Card -->
            <div
                class="bg-indigo-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200 overflow-hidden relative">
                <div class="absolute -right-8 -top-8 h-32 w-32 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <h3
                    class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-10 border-b border-indigo-800 pb-5">
                    Order Summary</h3>

                <div class="space-y-8">
                    <div class="flex items-start">
                        <div class="bg-indigo-800/50 p-3 rounded-2xl mr-4">
                            <svg class="h-5 w-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m0 10h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-indigo-400 font-black uppercase tracking-widest mb-1.5">Supplier
                            </p>
                            <p class="text-lg font-black leading-tight"><?= htmlspecialchars($data->supplierName) ?></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="bg-indigo-800/50 p-3 rounded-2xl mr-4">
                            <svg class="h-5 w-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-indigo-400 font-black uppercase tracking-widest mb-1.5">Purchase
                                Date
                            </p>
                            <p class="text-lg font-black leading-tight"><?= htmlspecialchars($data->buyDate) ?></p>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-indigo-800 group">
                        <p class="text-[10px] text-indigo-400 font-black uppercase tracking-[0.2em] mb-3">Total
                            Investment
                        </p>
                        <p
                            class="text-4xl font-black text-blue-400 italic group-hover:scale-105 transition-transform origin-left">
                            ฿<?= number_format($data->totalCost, 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-dashed border-slate-100 p-8">
                <div class="flex items-center mb-4 text-amber-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-xs font-black uppercase tracking-widest">Important Notice</span>
                </div>
                <p class="text-xs text-slate-400 font-bold leading-relaxed italic">
                    กรุณาตรวจสอบรายการสินค้าให้ถูกต้องก่อนกดยืนยันรับของ
                    เนื่องจากระบบจะทำการเพิ่มสต็อกสินค้าโดยอัตโนมัติลงในคลัง
                </p>
            </div>
        </div>
    </div>
</div>

</body>

</html>