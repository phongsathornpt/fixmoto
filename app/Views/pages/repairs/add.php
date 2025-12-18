<?php include_view('@layout/header.php'); ?>
<?php include_view('@layout/menu.php'); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex items-center">
        <?php render_component('ui/button', [
            'type' => 'link',
            'href' => '/repairs',
            'variant' => 'ghost',
            'size' => 'sm',
            'class' => 'mr-4 p-2.5',
            'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>'
        ], ''); ?>
        <h1 class="text-2xl font-bold text-slate-900">เพิ่มรายการงานซ่อมใหม่</h1>
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
    
    <div class="space-y-6">
        <!-- Step 1: Customer Lookup -->
        <?php ob_start(); ?>
            <div class="max-w-md">
                <label for="mobile" class="block text-sm font-semibold text-slate-700 mb-2">เบอร์โทรศัพท์ลูกค้า</label>
                <div class="flex space-x-3">
                    <input type="text" class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 focus:bg-white transition-all sm:text-sm" id="mobile" name="mobile" placeholder="ตัวอย่าง: 08XXXXXXXX">
                    <?php render_component('ui/button', [
                        'type' => 'button',
                        'onclick' => 'checkCustomer()',
                        'variant' => 'primary',
                        'size' => 'md',
                        'class' => 'px-6'
                    ], 'ตรวจสอบ'); ?>
                </div>
                <div id="customer-result" class="mt-4"></div>
            </div>
        <?php $step1 = ob_get_clean();
        render_component('ui/card', [
            'header_actions' => '<span class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-bold text-xs shadow-lg shadow-blue-100">1</span>',
            'title' => 'ตรวจสอบข้อมูลลูกค้า'
        ], $step1); ?>
        
        <!-- Step 2: Form (New Customer) -->
        <div id="new-customer-form" style="display:none;">
            <?php ob_start(); ?>
                <form method="post" action="/repairs/new-customer" class="space-y-6">
                    <input type="hidden" name="mobile" id="new_mobile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อ</label>
                            <input type="text" name="fname" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all text-slate-900 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">นามสกุล</label>
                            <input type="text" name="lname" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all text-slate-900 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 text-slate-900 font-medium">ทะเบียนรถ</label>
                            <input type="text" name="plate" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all text-slate-900 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 text-slate-900 font-medium">ยี่ห้อรถ</label>
                            <input type="text" name="brand" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all text-slate-900 font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 text-slate-900 font-medium">รายละเอียดความเสียหาย / งานซ่อม</label>
                        <textarea name="detail" rows="4" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all text-slate-900 font-medium" placeholder="แจ้งรายละเอียดอาการเสีย..."></textarea>
                    </div>
                    <div class="flex justify-end pt-4">
                        <?php render_component('ui/button', [
                            'type' => 'submit',
                            'variant' => 'success',
                            'size' => 'lg',
                            'class' => 'px-12'
                        ], 'ยืนยันการเพิ่มข้อมูล'); ?>
                    </div>
                </form>
            <?php $newCust = ob_get_clean();
            render_component('ui/card', [
                'header_actions' => '<span class="flex items-center justify-center h-8 w-8 rounded-full bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-100 border border-emerald-400">2</span>',
                'title' => 'ข้อมูลรถสำหรับลูกค้าใหม่',
                'class' => 'border-emerald-100 ring-4 ring-emerald-50/50'
            ], $newCust); ?>
        </div>
        
        <!-- Step 2: Form (Existing Customer) -->
        <div id="existing-customer-form" style="display:none;">
            <?php ob_start(); ?>
                <form method="post" action="/repairs/existing-customer" class="space-y-6">
                    <input type="hidden" name="customer_id" id="customer_id">
                    <div id="customer-info" class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 text-blue-900 font-bold mb-6 italic text-sm"></div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ทะเบียนรถ</label>
                            <input type="text" name="plate" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all font-medium text-slate-900">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ยี่ห้อรถ</label>
                            <input type="text" name="brand" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all font-medium text-slate-900">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">รายละเอียดความเสียหาย / งานซ่อม</label>
                        <textarea name="detail" rows="4" required class="block w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-slate-50 sm:text-sm transition-all font-medium text-slate-900" placeholder="แจ้งรายละเอียดอาการเสีย..."></textarea>
                    </div>
                    <div class="flex justify-end pt-4">
                        <?php render_component('ui/button', [
                            'type' => 'submit',
                            'variant' => 'primary',
                            'size' => 'lg',
                            'class' => 'px-12'
                        ], 'ยืนยันการเพิ่มข้อมูล'); ?>
                    </div>
                </form>
            <?php $existCust = ob_get_clean();
            render_component('ui/card', [
                'header_actions' => '<span class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-bold text-xs shadow-lg shadow-blue-100 border border-blue-400">2</span>',
                'title' => 'ข้อมูลรถสำหรับลูกค้าเดิม',
                'class' => 'border-blue-100 ring-4 ring-blue-50/50'
            ], $existCust); ?>
        </div>
    </div>
</div>

<script>
function checkCustomer() {
    const mobile = document.getElementById('mobile').value;
    
    fetch('/repairs/check-customer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'mobile=' + encodeURIComponent(mobile)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Existing customer
            document.getElementById('customer-result').innerHTML = '<div class="p-4 bg-blue-50 border border-blue-100 rounded-xl text-blue-700 text-sm font-bold flex items-center shadow-sm"><svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>พบลูกค้าเดิม: ' + data.customer.f_name + ' ' + data.customer.l_name + '</div>';
            document.getElementById('customer_id').value = data.customer.id;
            document.getElementById('customer-info').innerHTML = '<span class="flex items-center"><svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg> คุณ ' + data.customer.f_name + ' ' + data.customer.l_name + '</span>';
            document.getElementById('new-customer-form').style.display = 'none';
            document.getElementById('existing-customer-form').style.display = 'block';
            document.getElementById('existing-customer-form').scrollIntoView({ behavior: 'smooth' });
        } else {
            // New customer
            document.getElementById('customer-result').innerHTML = '<div class="p-4 bg-amber-50 border border-amber-100 rounded-xl text-amber-700 text-sm font-bold flex items-center shadow-sm"><svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>' + data.message + '</div>';
            document.getElementById('new_mobile').value = mobile;
            document.getElementById('new-customer-form').style.display = 'block';
            document.getElementById('existing-customer-form').style.display = 'none';
            document.getElementById('new-customer-form').scrollIntoView({ behavior: 'smooth' });
        }
    });
}
</script>

</body>
</html>
