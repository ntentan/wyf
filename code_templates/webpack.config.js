import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

function getFiles(dir, extensions) {
    if (!fs.existsSync(dir)) {
        return [];
    }
    return fs.readdirSync(dir, { recursive: true })
        .filter(file => {
            const ext = path.extname(file).toLowerCase();
            const basename = path.basename(file);
            return extensions.includes(ext) && !basename.startsWith('_') && !basename.endsWith('.min.js');
        })
        .map(file => path.resolve(dir, file));
}

// Collect SCSS/CSS from the fzui vendor package
const fzuiSassDir = path.resolve(__dirname, 'vendor/ekowabaka/ui/sass');
const fzuiStyles = fs.existsSync(fzuiSassDir)
    ? fs.readdirSync(fzuiSassDir)
        .filter(file => (file.endsWith('.scss') || file.endsWith('.css')) && !path.basename(file).startsWith('_'))
        .sort()
        .map(file => path.resolve(fzuiSassDir, file))
    : [];

if (fzuiStyles.length === 0 && fs.existsSync(path.resolve(__dirname, 'vendor/ekowabaka/ui/dist/fzui.css'))) {
    fzuiStyles.push(path.resolve(__dirname, 'vendor/ekowabaka/ui/dist/fzui.css'));
}

// Collect SCSS/CSS from assets/scss and assets/css
const appStyles = [
    ...getFiles(path.resolve(__dirname, 'assets/scss'), ['.scss', '.sass']),
    ...getFiles(path.resolve(__dirname, 'assets/css'), ['.css']),
];

// Collect JavaScript from the fzui vendor package
const fzuiScripts = [];
const fzuiDistJs = path.resolve(__dirname, 'vendor/ekowabaka/ui/dist/fzui.js');
if (fs.existsSync(fzuiDistJs)) {
    fzuiScripts.push(fzuiDistJs);
} else {
    const fzuiJsDir = path.resolve(__dirname, 'vendor/ekowabaka/ui/dist');
    if (fs.existsSync(fzuiJsDir)) {
        fzuiScripts.push(...getFiles(fzuiJsDir, ['.js']));
    }
}

// Collect JavaScript from src/js
const appScripts = getFiles(path.resolve(__dirname, 'src/js'), ['.js']);

const entries = [
    ...fzuiScripts,
    ...appScripts,
    ...fzuiStyles,
    ...appStyles,
];

export default {
    mode: 'production',
    entry: {
        app: entries.length > 0 ? entries : ['./src/js/main.js'],
    },
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: 'js/app.js',
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader',
                ],
            },
            {
                test: /\.css$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/app.css',
        }),
    ],
};
