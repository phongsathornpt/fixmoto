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
        <h1 class="text-2xl font-bold text-slate-900">เพิ่มรายการอะไหล่ใหม่</h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0 text-red-400">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-red-700"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php ob_start(); ?>
        <form action="/inventory" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="supplier" class="block text-sm font-bold text-slate-700 mb-2">ซัพพลายเออร์</label>
                    <div class="flex space-x-3">
                        <select name="supplier" id="supplier" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 focus:bg-white transition-all text-sm font-medium">
                            <option value="">เลือกซัพพลายเออร์</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php render_component('ui/button', [
                            'type' => 'link',
                            'href' => '/supplier/create',
                            'variant' => 'ghost',
                            'size' => 'md',
                            'class' => 'bg-slate-50 border-slate-200 hover:bg-slate-100'
                        ], '+ ใหม่'); ?>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">ชื่อรายการอะไหล่</label>
                    <input type="text" name="name" id="name" required placeholder="เช่น ยางนอก IRC 70/90-17" class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 focus:bg-white transition-all text-sm font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="cost" class="block text-sm font-bold text-slate-700 mb-2">ราคาต้นทุน</label>
                        <div class="relative rounded-xl overflow-hidden">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold text-sm">฿</span>
                            </div>
                            <input type="number" step="0.01" name="cost" id="cost" required class="block w-full pl-8 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 focus:bg-white transition-all text-sm font-bold" placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-bold text-slate-700 mb-2">ราคาขายปลีก</label>
                        <div class="relative rounded-xl overflow-hidden">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-500 font-bold text-sm">฿</span>
                            </div>
                            <input type="number" step="0.01" name="price" id="price" required class="block w-full pl-8 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 focus:bg-white transition-all text-sm font-bold text-blue-600" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-100">
                <?php render_component('ui/button', [
                    'type' => 'submit',
                    'variant' => 'primary',
                    'size' => 'lg',
                    'class' => 'px-12'
                ], 'บันทึกข้อมูลอะไหล่'); ?>
            </div>
        </form>
    <?php $formContent = ob_get_clean();
    render_component('ui/card', [], $formContent); ?>
</div>

</body>
</html>
