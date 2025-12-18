<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden mb-12">
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                ยินดีต้อนรับสู่ <span class="text-blue-600">FixMoto</span>
            </h1>
            <p class="text-lg text-slate-500 max-w-2xl mb-10 leading-relaxed">
                ยกระดับการจัดการร้านซ่อมมอเตอร์ไซค์ของคุณด้วยระบบอัตโนมัติ ติดตามงานซ่อม อะไหล่ และใบสั่งซื้อได้ในที่เดียว
            </p>
            
            <div class="flex flex-wrap gap-4">
                <?php render_component('ui/button', [
                    'type' => 'link',
                    'href' => '/repairs/create',
                    'variant' => 'primary',
                    'size' => 'lg',
                    'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'
                ], 'เปิดงานซ่อมใหม่'); ?>
            </div>
        </div>

        <!-- Decoration -->
        <div class="absolute -top-24 -right-24 h-64 w-64 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute -bottom-24 -left-24 h-64 w-64 bg-slate-50 rounded-full blur-3xl opacity-50"></div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php 
        $actions = [
            [
                'title' => 'งานซ่อม',
                'desc' => 'ดูรายการงานซ่อมทั้งหมดและสถานะปัจจุบัน',
                'href' => '/repairs',
                'color' => 'blue',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />'
            ],
            [
                'title' => 'คลังอะไหล่',
                'desc' => 'จัดการจำนวนอะไหล่และตรวจสอบสินค้าคงเหลือ',
                'href' => '/inventory',
                'color' => 'emerald',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />'
            ],
            [
                'title' => 'รายการสั่งซื้อ',
                'desc' => 'ติดตามการสั่งซื้ออะไหล่จากซัพพลายเออร์',
                'href' => '/purchase',
                'color' => 'indigo',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />'
            ]
        ];

        foreach ($actions as $action): 
            ob_start(); ?>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="p-3 bg-<?= $action['color'] ?>-100 rounded-xl text-<?= $action['color'] ?>-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <?= $action['icon'] ?>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900"><?= $action['title'] ?></h3>
                </div>
                <p class="text-slate-500 mb-6"><?= $action['desc'] ?></p>
                <?php render_component('ui/button', [
                    'type' => 'link',
                    'href' => $action['href'],
                    'variant' => 'ghost',
                    'size' => 'sm',
                    'class' => 'w-full'
                ], 'เข้าหน้าจัดการ'); ?>
            <?php $cardContent = ob_get_clean();
            render_component('ui/card', ['class' => 'hover:border-blue-300 transition-all group'], $cardContent);
        endforeach; ?>
    </div>
</main>

</body>
</html>
