<?php

class Remover
{
    
    public static function allInDirectory($path)
    {
        if (is_dir($path)) {
            $allFiles = glob($path . '/*');
            
            foreach ($allFiles as $file) {
                if (! is_file($file)) {
                    self::allInDirectory($file);
                    rmdir($file);
                } else {
                    unlink($file);
                }
            }
        }
    }
}