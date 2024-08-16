const path = require('path')

module.exports = {
    entry: './js/wyf.js',
    mode: 'development',
    devtool: 'source-map',
    output: {
        filename: 'wyf.js',
        path: path.resolve(__dirname, 'assets/js')
    }
}