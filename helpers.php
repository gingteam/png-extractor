<?php

declare(strict_types=1);

if (!function_exists('path')) {
    function path(?string $path): string {
        return getcwd().($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
