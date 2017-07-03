<?php
namespace ntentan\wyf;

use ntentan\dev\assets\Asset;

class WyfAssets {
    
    public static function css() {
        return Asset::css([
            'vendor/fortawesome/font-awesome/css/font-awesome.css',
            Asset::sass(['vendor/ekowabaka/fzui/sass/fzui.scss', 'vendor/ekowabaka/fzui/sass/*.scss']),
            realpath(__DIR__ . '/../assets/css/wyf.css'),
            realpath(__DIR__ . '/../assets/css/menu.css')
        ]);
    }
    
    public static function js() {
        return Asset::js([
            'vendor/frameworks/jquery/jquery.js',
            realpath(__DIR__ . '/../assets/js/api.js'),
            realpath(__DIR__ . '/../assets/js/wyf.js'),
            'vendor/frameworks/handlebars.js/handlebars.js',
            'vendor/ekowabaka/fzui/js/fzui.js',
            'vendor/ekowabaka/fzui/js/dropdown.js',
            'vendor/ekowabaka/fzui/js/modal.js',
            'vendor/ekowabaka/fzui/js/nav.js',
        ]);
    }
    
}