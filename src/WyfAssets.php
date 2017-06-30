<?php
namespace ntentan\wyf;

use ntentan\dev\assets\Asset;

class WyfAssets {
    
    public static function css() {
        return Asset::css([
            'vendor/fortawesome/font-awesome/css/font-awesome.css',
            'vendor/ekowabaka/fzui/dist/fzui.min.css',
            realpath(__DIR__ . '/../assets/css/wyf.css'),
            realpath(__DIR__ . '/../assets/css/menu.css')
        ]);
    }
    
    public static function js() {
        return Asset::js([
            'components/jquery/jquery.js',
            realpath(__DIR__ . '/../assets/js/api.js'),
            realpath(__DIR__ . '/../assets/js/wyf.js'),
            'components/handlebars/handlebars.js',
            'vendor/ekowabaka/fzui/dist/fzui.min.js'
        ]);
    }
    
}