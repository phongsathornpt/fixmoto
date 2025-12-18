<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">เพิ่มซัพพลายเออร์</h1>
        <p class="text-slate-500 font-medium">เพิ่มรายชื่อผู้จำหน่ายเพื่อผูกกับรายการอะไหล่ในคลัง</p>
    </div>

    <?php ob_start(); ?>
        <form action="/supplier" method="POST" class="space-y-8">
            <div>
                <label for="name" class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Supplier Name</label>
                <input type="text" name="name" id="name" required placeholder="เช่น ร้านอะไหล่เจริญพงศ์" class="block w-full border-slate-200 rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 bg-slate-50 focus:bg-white transition-all text-base font-bold text-slate-900 placeholder:text-slate-300">
            </div>

            <div class="flex flex-col space-y-4 pt-4">
                <?php render_component('ui/button', [
                    'type' => 'submit',
                    'variant' => 'primary',
                    'size' => 'lg',
                    'class' => 'w-full py-5 shadow-xl shadow-blue-200'
                ], 'บันทึกข้อมูล'); ?>
                <?php render_component('ui/button', [
                    'type' => 'link',
                    'href' => '/inventory/create',
                    'variant' => 'ghost',
                    'class' => 'w-full text-slate-400 font-bold'
                ], 'ยกเลิก'); ?>
            </div>
        </form>
    <?php $formContent = ob_get_clean();
    render_component('ui/card', ['class' => 'p-2 shadow-2xl shadow-slate-200 ring-4 ring-slate-50/50'], $formContent); ?>
</div>

</body>
</html>
