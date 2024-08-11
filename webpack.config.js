const path = require('path')

module.exports = {
    entry: './js/wyf.js',
    mode: 'development',
    output: {
        filename: 'wyf.js',
        path: path.resolve(__dirname, 'assets/js')
    }
}