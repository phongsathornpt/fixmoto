<?php

/**
 * Docblock Checker Utility
 * 
 * Analyzes PHP files for missing docblock return types in methods.
 * This helps maintain code quality and documentation standards.
 */
class DocblockChecker {
    /**
     * @var array Collected warnings
     */
    private $warnings = [];
    
    /**
     * @var bool Whether to check protected methods
     */
    private $checkProtected = true;
    
    /**
     * @var bool Whether to check private methods
     */
    private $checkPrivate = false;

    /**
     * Check a single PHP file for missing docblock return types
     * 
     * @param string $filePath Absolute path to the PHP file
     * @return array Array of warnings found
     */
    public function checkFile(string $filePath): array {
        $this->warnings = [];
        
        if (!file_exists($filePath)) {
            $this->warnings[] = [
                'file' => $filePath,
                'line' => 0,
                'method' => '',
                'message' => 'File not found'
            ];
            return $this->warnings;
        }
        
        $content = file_get_contents($filePath);
        $tokens = token_get_all($content);
        
        $this->analyzeTokens($tokens, $filePath);
        
        return $this->warnings;
    }
    
    /**
     * Check all PHP files in a directory
     * 
     * @param string $directory Directory path to scan
     * @param bool $recursive Whether to scan subdirectories
     * @return array Array of warnings found
     */
    public function checkDirectory(string $directory, bool $recursive = true): array {
        $allWarnings = [];
        
        if (!is_dir($directory)) {
            return [[
                'file' => $directory,
                'line' => 0,
                'method' => '',
                'message' => 'Directory not found'
            ]];
        }
        
        $files = $recursive 
            ? $this->getPhpFilesRecursive($directory)
            : glob($directory . '/*.php');
        
        foreach ($files as $file) {
            $warnings = $this->checkFile($file);
            $allWarnings = array_merge($allWarnings, $warnings);
        }
        
        return $allWarnings;
    }
    
    /**
     * Get all PHP files recursively from a directory
     * 
     * @param string $directory Directory to scan
     * @return array List of PHP file paths
     */
    private function getPhpFilesRecursive(string $directory): array {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }
    
    /**
     * Analyze PHP tokens for missing docblock return types
     * 
     * @param array $tokens PHP tokens from token_get_all
     * @param string $filePath Path of file being analyzed
     * @return void
     */
    private function analyzeTokens(array $tokens, string $filePath): void {
        $currentClass = null;
        $lastDocblock = null;
        $lastDocblockLine = 0;
        $i = 0;
        $tokenCount = count($tokens);
        
        while ($i < $tokenCount) {
            $token = $tokens[$i];
            
            if (!is_array($token)) {
                $i++;
                continue;
            }
            
            $tokenType = $token[0];
            $tokenValue = $token[1];
            $tokenLine = $token[2];
            
            // Track current class
            if ($tokenType === T_CLASS || $tokenType === T_INTERFACE || $tokenType === T_TRAIT) {
                $currentClass = $this->findNextIdentifier($tokens, $i);
            }
            
            // Track docblocks
            if ($tokenType === T_DOC_COMMENT) {
                $lastDocblock = $tokenValue;
                $lastDocblockLine = $tokenLine;
            }
            
            // Check function declarations
            if ($tokenType === T_FUNCTION) {
                $visibility = $this->findPreviousVisibility($tokens, $i);
                $functionName = $this->findNextIdentifier($tokens, $i);
                $hasNativeReturnType = $this->hasNativeReturnType($tokens, $i);
                
                // Skip constructors and destructors
                if (in_array($functionName, ['__construct', '__destruct'])) {
                    $i++;
                    continue;
                }
                
                // Skip based on visibility settings
                if ($visibility === 'private' && !$this->checkPrivate) {
                    $i++;
                    continue;
                }
                
                if ($visibility === 'protected' && !$this->checkProtected) {
                    $i++;
                    continue;
                }
                
                // Check if method has return type declaration
                $hasDocblockReturn = false;
                $docblockIsRecent = ($tokenLine - $lastDocblockLine) < 10;
                
                if ($lastDocblock && $docblockIsRecent) {
                    $hasDocblockReturn = $this->docblockHasReturn($lastDocblock);
                }
                
                // Report missing return type
                if (!$hasNativeReturnType && !$hasDocblockReturn) {
                    $context = $currentClass ? "{$currentClass}::{$functionName}" : $functionName;
                    $this->warnings[] = [
                        'file' => $filePath,
                        'line' => $tokenLine,
                        'method' => $context,
                        'message' => "Method \"{$context}\" is missing docblock return type"
                    ];
                }
                
                // Reset docblock after processing method
                $lastDocblock = null;
            }
            
            $i++;
        }
    }
    
    /**
     * Find the next identifier token after current position
     * 
     * @param array $tokens Token array
     * @param int $startIndex Starting index
     * @return string|null The identifier name or null
     */
    private function findNextIdentifier(array $tokens, int $startIndex): ?string {
        $count = count($tokens);
        
        for ($i = $startIndex + 1; $i < $count; $i++) {
            if (!is_array($tokens[$i])) {
                continue;
            }
            
            if ($tokens[$i][0] === T_STRING) {
                return $tokens[$i][1];
            }
        }
        
        return null;
    }
    
    /**
     * Find visibility modifier before function keyword
     * 
     * @param array $tokens Token array
     * @param int $functionIndex Index of function keyword
     * @return string Visibility (public, protected, private)
     */
    private function findPreviousVisibility(array $tokens, int $functionIndex): string {
        for ($i = $functionIndex - 1; $i >= 0 && $i >= $functionIndex - 5; $i--) {
            if (!is_array($tokens[$i])) {
                continue;
            }
            
            switch ($tokens[$i][0]) {
                case T_PUBLIC:
                    return 'public';
                case T_PROTECTED:
                    return 'protected';
                case T_PRIVATE:
                    return 'private';
            }
        }
        
        return 'public'; // Default visibility
    }
    
    /**
     * Check if function has native PHP return type declaration
     * 
     * @param array $tokens Token array
     * @param int $functionIndex Index of function keyword
     * @return bool Whether a native return type exists
     */
    private function hasNativeReturnType(array $tokens, int $functionIndex): bool {
        $count = count($tokens);
        $foundCloseParen = false;
        
        for ($i = $functionIndex; $i < $count && $i < $functionIndex + 50; $i++) {
            // Look for the closing parenthesis of parameters
            if ($tokens[$i] === ')') {
                $foundCloseParen = true;
                continue;
            }
            
            // After closing paren, look for : which indicates return type
            if ($foundCloseParen) {
                if ($tokens[$i] === ':') {
                    return true;
                }
                
                // Function body started without return type
                if ($tokens[$i] === '{') {
                    return false;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if docblock contains @return annotation
     * 
     * @param string $docblock The docblock content
     * @return bool Whether @return is present
     */
    private function docblockHasReturn(string $docblock): bool {
        return preg_match('/@return\s+\S+/', $docblock) === 1;
    }
    
    /**
     * Set whether to check protected methods
     * 
     * @param bool $check Whether to check
     * @return self
     */
    public function setCheckProtected(bool $check): self {
        $this->checkProtected = $check;
        return $this;
    }
    
    /**
     * Set whether to check private methods
     * 
     * @param bool $check Whether to check
     * @return self
     */
    public function setCheckPrivate(bool $check): self {
        $this->checkPrivate = $check;
        return $this;
    }
    
    /**
     * Format warnings as a readable report
     * 
     * @param array $warnings Array of warnings
     * @return string Formatted report
     */
    public static function formatReport(array $warnings): string {
        if (empty($warnings)) {
            return "✅ No missing docblock return types found.\n";
        }
        
        $report = "⚠️ Found " . count($warnings) . " missing docblock return type(s):\n\n";
        
        $groupedByFile = [];
        foreach ($warnings as $warning) {
            $file = $warning['file'];
            if (!isset($groupedByFile[$file])) {
                $groupedByFile[$file] = [];
            }
            $groupedByFile[$file][] = $warning;
        }
        
        foreach ($groupedByFile as $file => $fileWarnings) {
            $report .= "📁 " . basename($file) . "\n";
            foreach ($fileWarnings as $warning) {
                $report .= "   Line {$warning['line']}: {$warning['message']}\n";
            }
            $report .= "\n";
        }
        
        return $report;
    }
    
    /**
     * Run check and output formatted report
     * 
     * @param string $path File or directory path
     * @return int Number of warnings (for exit code)
     */
    public static function run(string $path): int {
        $checker = new self();
        
        if (is_dir($path)) {
            $warnings = $checker->checkDirectory($path);
        } else {
            $warnings = $checker->checkFile($path);
        }
        
        echo self::formatReport($warnings);
        
        return count($warnings);
    }
}
