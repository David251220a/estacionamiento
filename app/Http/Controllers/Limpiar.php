<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class Limpiar extends Controller
{
    public function limpiar()
    {
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        // Artisan::call('migrate', ['--force' => true]);
        // return "Migraciones ejecutadas correctamente.";
        // Artisan::call('db:seed', ['--force' => true]);
        return "Migraciones ejecutadas correctamente.";
    }

    public function acceso()
    {
        // $target = '/home/wweixrni/fundacion/storage/app/public';
        // $shortcut = '/home/wweixrni/public_html/storage';
        // symlink($target, $shortcut);
    }
}
