const ExtractTextPlugin = require('extract-text-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const webpack = require('webpack');
const path = require('path');

module.exports = {
    entry: [
        "font-awesome-sass-loader",
        "bootstrap-loader",
        "./assets/scss/base.scss",
        "./assets/js/app.js",
        "./assets/js/sidemenu.js",
        "./assets/js/autosave.js",
        "./assets/js/tags.js"
    ],
    output: {
        path: path.resolve(__dirname, 'web/public'),
        filename: 'scripts.js'
    },
    resolve: {
        extensions: [ '.js' ]
    },
    plugins: [
        new ExtractTextPlugin({ filename: 'styles.css', allChunks: true }),
        new OptimizeCssAssetsPlugin(),
        new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, mangle: false })
    ],
    module: {
        loaders: [
            { test: /\.scss$/, loader: ExtractTextPlugin.extract({ fallback: 'style-loader', use: 'css-loader!postcss-loader!sass-loader'}) },
            { test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "url-loader" },
            { test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/, loader: 'file-loader' },
            { test: /bootstrap-sass\/assets\/javascripts\//, loader: 'imports-loader?jQuery=jquery' }
        ]
    },
    performance: { hints: false },
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    }
};
