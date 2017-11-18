<?php
namespace ntentan\wyf;

use ntentan\dev\assets\Asset;

class WyfAssets {
    
    public static function css() {
        return Asset::css([
            'vendor/fortawesome/font-awesome/css/font-awesome.css',
            Asset::sass(['vendor/fahodzi/ui/sass/fzui.scss', 'vendor/fahodzi/ui/sass/*.scss']),
            realpath(__DIR__ . '/../assets/css/wyf.css'),
            realpath(__DIR__ . '/../assets/css/menu.css')
        ]);
    }
    
    public static function js() {
        return Asset::js([
            'vendor/frameworks/jquery/jquery.js',
            realpath(__DIR__ . '/../assets/js/api.js'),
            realpath(__DIR__ . '/../assets/js/wyf.js'),
            'vendor/webmodules/moment/moment.js',
            'vendor/webmodules/pikaday/pikaday.js',
            'vendor/webmodules/pikaday/plugins/pikaday.jquery.js',
            'vendor/frameworks/handlebars.js/handlebars.js',
            'vendor/fahodzi/ui/js/fzui.js',
            'vendor/fahodzi/ui/js/dropdown.js',
            'vendor/fahodzi/ui/js/modal.js',
            'vendor/fahodzi/ui/js/nav.js',
        ]);
    }
    
}