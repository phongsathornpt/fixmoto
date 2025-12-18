<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - FIXMOTO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 selection:bg-blue-100 selection:text-blue-700">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Ornaments -->
        <div class="absolute -top-24 -left-24 h-96 w-96 bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-24 h-64 w-64 bg-indigo-500/5 rounded-full blur-3xl"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative">
            <div class="text-center mb-10">
                <div class="inline-flex h-20 w-20 bg-blue-600 rounded-[2rem] items-center justify-center shadow-2xl shadow-blue-200 mb-6 group hover:scale-105 transition-transform duration-500">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">FIXMOTO</h2>
                <p class="mt-2 text-slate-500 font-medium">Smart Garage Management System</p>
            </div>

            <?php ob_start(); ?>
                <form class="space-y-6" action="/login" method="POST">
                    <div>
                        <label for="username" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2.5">User Access ID</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-blue-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <input id="username" name="username" type="text" required class="block w-full pl-11 border-slate-200 rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 bg-slate-50 hover:bg-white transition-all text-sm font-bold placeholder:text-slate-300" placeholder="Username">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2.5">Security Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-blue-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input id="password" name="password" type="password" required class="block w-full pl-11 border-slate-200 rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 bg-slate-50 hover:bg-white transition-all text-sm font-bold placeholder:text-slate-300" placeholder="••••••••">
                        </div>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="bg-red-50 p-4 rounded-xl border border-red-100 flex items-center text-red-600">
                             <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                             <span class="text-xs font-black uppercase tracking-wider"><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="pt-2">
                        <?php render_component('ui/button', [
                            'type' => 'submit',
                            'variant' => 'primary',
                            'size' => 'lg',
                            'class' => 'w-full py-5 rounded-2xl shadow-2xl shadow-blue-200 ring-4 ring-blue-50 transform active:scale-95 transition-all'
                        ], 'Authenticate & Unlock'); ?>
                    </div>
                </form>
            <?php $loginForm = ob_get_clean();
            render_component('ui/card', ['class' => 'px-2 py-4 shadow-2xl shadow-slate-200 ring-8 ring-white'], $loginForm); ?>
            
            <p class="mt-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
                &copy; <?= date('Y') ?> FIXMOTO PLATFORM. ALL RIGHTS RESERVED.
            </p>
        </div>
    </div>
</body>
</html>
