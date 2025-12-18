<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 flex items-center">
            <?php render_component('ui/button', [
                'type' => 'link',
                'href' => '/repairs',
                'variant' => 'ghost',
                'size' => 'sm',
                'class' => 'mr-4 p-2.5',
                'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>'
            ], ''); ?>
            รายละเอียดงานซ่อม <span class="text-blue-600 ml-2">#<?= htmlspecialchars($repair['id']) ?></span>
        </h1>
        <?php render_component('ui/badge', [
            'variant' => ($status ?? '') === 'เสร็จสิ้น' ? 'emerald' : 'blue',
            'class' => 'px-4 py-1.5'
        ], htmlspecialchars($status ?? 'กำลังดำเนินการ')); ?>
    </div>

    <div class="space-y-6">
        <!-- Main Info -->
        <?php ob_start(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1 tracking-wider">เจ้าของรถ</p>
                    <p class="text-lg font-bold text-slate-900 italic">
                        <?php if ($customer): ?>
                            คุณ <?= htmlspecialchars($customer['f_name'] . ' ' . $customer['l_name']) ?>
                        <?php else: ?>
                            <span class="text-slate-400">ไม่พบข้อมูลลูกค้า</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1 tracking-wider">ยานพาหนะ</p>
                    <p class="text-lg font-bold text-slate-900"><?= htmlspecialchars($repair['brand']) ?></p>
                    <p class="text-sm text-slate-500 mt-0.5 font-medium">ทะเบียน: <span class="text-blue-600"><?= htmlspecialchars($repair['plate']) ?></span></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1 tracking-wider">วันที่รับงาน</p>
                    <p class="text-base font-semibold text-slate-800"><?= htmlspecialchars($repair['date']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1 tracking-wider">อาการ / รายละเอียด</p>
                    <p class="text-base text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100"><?= nl2br(htmlspecialchars($repair['detail'])) ?></p>
                </div>
            </div>
        <?php $mainInfo = ob_get_clean();
        render_component('ui/card', ['title' => 'ข้อมูลรายละเอียดงาน'], $mainInfo); ?>

        <!-- Parts Usage -->
        <?php ob_start(); ?>
            <?php if (!empty($usedParts)): ?>
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500">
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">รายการอะไหล่</th>
                                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-right">รหัสชิ้นส่วน</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($usedParts as $part): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-900"><?= htmlspecialchars($part['name']) ?></td>
                                    <td class="px-6 py-4 text-slate-500 text-right font-mono text-sm tracking-tight"><?= htmlspecialchars($part['serial_number']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="py-12 text-center text-slate-400">
                    <div class="bg-slate-50 h-16 w-16 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <svg class="h-8 w-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="font-medium text-sm">ยังไม่มีการเบิกใช้อะไหล่สำหรับงานนี้</p>
                </div>
            <?php endif; ?>
        <?php $partsContent = ob_get_clean();
        render_component('ui/card', [
            'title' => 'อะไหล่ที่เกี่ยวขอ้อง',
            'subtitle' => count($usedParts) . ' รายการ'
        ], $partsContent); ?>
    </div>
</div>

</body>
</html>
