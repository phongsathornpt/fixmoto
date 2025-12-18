<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">รายการซ่อม</h1>
            <p class="text-slate-500 mt-1">จัดการและติดตามสถานะงานซ่อมทั้งหมด</p>
        </div>
        <?php render_component('ui/button', [
            'type' => 'link',
            'href' => '/repairs/create',
            'variant' => 'primary',
            'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'
        ], 'เพิ่มงานซ่อม'); ?>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($repairs as $repair): 
            ob_start(); ?>
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="bg-slate-100 p-2 rounded-lg group-hover:bg-blue-50 transition-colors">
                            <svg class="h-6 w-6 text-slate-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-slate-900">รหัสงาน: #<?= htmlspecialchars($repair['id']) ?></h3>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($repair['date']) ?></p>
                        </div>
                    </div>
                    <?php render_component('ui/badge', [
                        'variant' => ($repair['status'] ?? '') === 'เสร็จสิ้น' ? 'emerald' : 'blue'
                    ], htmlspecialchars($repair['status'] ?? 'กำลังดำเนินการ')); ?>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-sm">
                        <svg class="h-4 w-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-slate-600 font-medium"><?= htmlspecialchars($repair['customer_name'] ?? 'ไม่ระบุชื่อ') ?></span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="h-4 w-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <span class="text-slate-600"><?= htmlspecialchars($repair['brand']) ?> - <?= htmlspecialchars($repair['plate']) ?></span>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <?php render_component('ui/button', [
                        'type' => 'link',
                        'href' => '/repairs/' . $repair['id'],
                        'variant' => 'ghost',
                        'size' => 'sm',
                        'class' => 'text-blue-600 hover:text-blue-700',
                        'icon' => '<svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>'
                    ], 'ดูรายละเอียด'); ?>
                </div>
            <?php $cardContent = ob_get_clean();
            render_component('ui/card', ['class' => 'hover:shadow-md transition-shadow group'], $cardContent);
        endforeach; ?>
    </div>
</div>

</body>
</html>
