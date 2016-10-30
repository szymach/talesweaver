const ExtractTextPlugin = require('extract-text-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const webpack = require('webpack');

module.exports = {
    entry: [
        'font-awesome-sass-loader',
        "bootstrap-loader",
        "./assets/scss/base.scss",
        "./assets/js/app.js",
        "./assets/js/tags.js"
    ],
    output: {
        path: 'web/public',
        filename: "app.js"
    },
    resolve: {
        extensions: [ '.js' ]
    },
    plugins: [
        new ExtractTextPlugin({ filename: 'app.css', allChunks: true }),
        new OptimizeCssAssetsPlugin(),
        new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, mangle: false })
    ],
    module: {
        loaders: [
            { test: /\.scss$/, loader: ExtractTextPlugin.extract({ fallbackLoader: 'style', loader: 'css!postcss!sass'}) },
            { test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "url" },
            { test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/, loader: 'file' },
            { test: /bootstrap-sass\/assets\/javascripts\//, loader: 'imports?jQuery=jquery' },
        ]
    }
};
