<?php

/**
 * Premium Error Handler
 * 
 * Displays PHP errors in a beautiful, user-friendly format.
 */
class ErrorHandler {
    
    private static bool $debug = true;
    
    /**
     * Register the error handler
     */
    public static function register(bool $debug = true): void {
        self::$debug = $debug;
        
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    /**
     * Handle PHP errors
     */
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    
    /**
     * Handle uncaught exceptions
     */
    public static function handleException(Throwable $exception): void {
        http_response_code(500);
        
        if (self::$debug) {
            self::renderDebugPage($exception);
        } else {
            self::renderProductionPage();
        }
        
        exit;
    }
    
    /**
     * Handle fatal errors
     */
    public static function handleShutdown(): void {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleException(
                new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
            );
        }
    }
    
    /**
     * Render beautiful debug error page
     */
    private static function renderDebugPage(Throwable $exception): void {
        $errorType = self::getErrorType($exception);
        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();
        $trace = $exception->getTrace();
        $codeSnippet = self::getCodeSnippet($file, $line);
        
        ?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - <?= htmlspecialchars($errorType) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        code, .code { font-family: 'JetBrains Mono', monospace; }
        .line-highlight { background: rgba(239, 68, 68, 0.15); border-left: 3px solid #ef4444; }
    </style>
</head>
<body class="h-full bg-slate-900 text-white">
    <div class="min-h-full flex flex-col">
        <!-- Header -->
        <header class="bg-red-600 px-6 py-4 shadow-lg shadow-red-900/30">
            <div class="max-w-6xl mx-auto flex items-center gap-4">
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tight uppercase"><?= htmlspecialchars($errorType) ?></h1>
                    <p class="text-red-100 text-sm font-medium opacity-80">Something went wrong</p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-auto">
            <div class="max-w-6xl mx-auto px-6 py-8 space-y-8">
                
                <!-- Error Message -->
                <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 shadow-xl">
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Error Message</h2>
                    <p class="text-xl font-bold text-red-400 leading-relaxed"><?= htmlspecialchars($message) ?></p>
                </div>
                
                <!-- File Location -->
                <div class="bg-slate-800 rounded-2xl p-6 border border-slate-700 shadow-xl">
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Location</h2>
                    <div class="flex items-center gap-3 bg-slate-900 rounded-xl p-4">
                        <div class="bg-amber-500/20 text-amber-400 p-2 rounded-lg">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="code text-sm text-slate-300 truncate"><?= htmlspecialchars($file) ?></p>
                            <p class="text-xs text-slate-500 mt-0.5">Line <span class="text-red-400 font-bold"><?= $line ?></span></p>
                        </div>
                    </div>
                </div>
                
                <!-- Code Snippet -->
                <?php if (!empty($codeSnippet)): ?>
                <div class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 shadow-xl">
                    <div class="px-6 py-4 border-b border-slate-700 flex items-center justify-between">
                        <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest">Code Preview</h2>
                        <span class="code text-xs text-slate-500"><?= basename($file) ?></span>
                    </div>
                    <div class="overflow-x-auto">
                        <pre class="text-sm leading-relaxed"><code><?php 
                            foreach ($codeSnippet as $lineNum => $code): 
                                $isErrorLine = $lineNum === $line;
                                $lineClass = $isErrorLine ? 'line-highlight bg-red-500/10' : '';
                        ?><div class="px-6 py-1 flex <?= $lineClass ?>"><span class="text-slate-500 w-12 flex-shrink-0 select-none text-right pr-4"><?= $lineNum ?></span><span class="<?= $isErrorLine ? 'text-red-300' : 'text-slate-300' ?>"><?= htmlspecialchars($code) ?></span></div><?php 
                            endforeach; 
                        ?></code></pre>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Stack Trace -->
                <?php if (!empty($trace)): ?>
                <div class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 shadow-xl">
                    <div class="px-6 py-4 border-b border-slate-700">
                        <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest">Stack Trace</h2>
                    </div>
                    <div class="divide-y divide-slate-700/50">
                        <?php foreach (array_slice($trace, 0, 10) as $index => $frame): ?>
                        <div class="px-6 py-4 hover:bg-slate-700/30 transition-colors">
                            <div class="flex items-start gap-4">
                                <span class="bg-slate-700 text-slate-400 text-xs font-bold rounded-lg px-2.5 py-1 mt-0.5">#<?= $index ?></span>
                                <div class="flex-1 overflow-hidden">
                                    <?php if (isset($frame['class'])): ?>
                                    <p class="code text-sm">
                                        <span class="text-cyan-400"><?= htmlspecialchars($frame['class']) ?></span><span class="text-slate-500"><?= $frame['type'] ?? '::' ?></span><span class="text-emerald-400"><?= htmlspecialchars($frame['function']) ?></span><span class="text-slate-400">()</span>
                                    </p>
                                    <?php else: ?>
                                    <p class="code text-sm">
                                        <span class="text-emerald-400"><?= htmlspecialchars($frame['function'] ?? 'Unknown') ?></span><span class="text-slate-400">()</span>
                                    </p>
                                    <?php endif; ?>
                                    <?php if (isset($frame['file'])): ?>
                                    <p class="text-xs text-slate-500 mt-1 truncate"><?= htmlspecialchars($frame['file']) ?>:<span class="text-amber-400"><?= $frame['line'] ?? '?' ?></span></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-slate-800/50 border-t border-slate-700 px-6 py-4">
            <div class="max-w-6xl mx-auto flex items-center justify-between text-xs text-slate-500">
                <span>PHP <?= PHP_VERSION ?></span>
                <span class="font-bold text-slate-400">FIXMOTO Error Handler</span>
                <span><?= date('Y-m-d H:i:s') ?></span>
            </div>
        </footer>
    </div>
</body>
</html>
        <?php
    }
    
    /**
     * Render simple production error page
     */
    private static function renderProductionPage(): void {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
    <div class="text-center max-w-md">
        <div class="bg-red-100 text-red-600 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1 class="text-2xl font-black text-slate-900 mb-2">เกิดข้อผิดพลาด</h1>
        <p class="text-slate-500 mb-6">ระบบพบปัญหาบางอย่าง กรุณาลองใหม่อีกครั้ง</p>
        <a href="/" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
            กลับหน้าหลัก
        </a>
    </div>
</body>
</html>
        <?php
    }
    
    /**
     * Get error type name
     */
    private static function getErrorType(Throwable $exception): string {
        if ($exception instanceof ErrorException) {
            $severity = $exception->getSeverity();
            return match($severity) {
                E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR => 'Fatal Error',
                E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING => 'Warning',
                E_NOTICE, E_USER_NOTICE => 'Notice',
                E_PARSE => 'Parse Error',
                default => 'Error'
            };
        }
        
        return get_class($exception);
    }
    
    /**
     * Get code snippet around error line
     */
    private static function getCodeSnippet(string $file, int $line, int $range = 5): array {
        if (!file_exists($file) || !is_readable($file)) {
            return [];
        }
        
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $start = max(0, $line - $range - 1);
        $end = min(count($lines), $line + $range);
        
        $snippet = [];
        for ($i = $start; $i < $end; $i++) {
            $snippet[$i + 1] = $lines[$i];
        }
        
        return $snippet;
    }
}
