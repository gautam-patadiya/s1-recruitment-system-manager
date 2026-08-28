var webpack = require('webpack');

const apiUrl = process.env.VUE_APP_API_URL || 'http://localhost:8000';
const devServerHost = process.env.VUE_APP_DEV_SERVER_HOST || 'localhost';
const devServerPort = process.env.VUE_APP_DEV_SERVER_PORT || 8080;

module.exports = {
    // proxy API requests to Valet during development
    devServer: {
        host: devServerHost,
        port: devServerPort,
        proxy: apiUrl
    },

    // output built static files to Laravel's public dir.
    // note the "build" script in package.json needs to be modified as well.
    outputDir: '../public',

    // modify the location of the generated HTML file.
    // make sure to do this only in production.
    indexPath: process.env.NODE_ENV === 'production'
        ? '../resources/views/index.blade.php'
        : 'index.html',

    configureWebpack: {
        plugins: [
            new webpack.ProvidePlugin({
                'window.Quill': 'quill/dist/quill.js',
                'Quill': 'quill/dist/quill.js'
            }),
        ]
    }
}
