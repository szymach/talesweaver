const ExtractTextPlugin = require('extract-text-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const webpack = require('webpack');

module.exports = {
    entry: {
        main: [
            "./assets/scss/base.scss",
            "./assets/js/app.js",
            "./assets/js/tags.js"
        ],
        vendor: ['font-awesome-sass-loader', "bootstrap-loader"]
    },
    output: {
        path: 'web/public',
        filename: '[name].js'
    },
    resolve: {
        extensions: [ '.js' ]
    },
    plugins: [
        new ExtractTextPlugin({ filename: '[name].css', allChunks: true }),
        new OptimizeCssAssetsPlugin(),
        new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, mangle: false })
    ],
    module: {
        loaders: [
            { test: /\.scss$/, loader: ExtractTextPlugin.extract({ fallbackLoader: 'style-loader', loader: 'css-loader!postcss-loader!sass-loader'}) },
            { test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "url-loader" },
            { test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/, loader: 'file-loader' },
            { test: /bootstrap-sass\/assets\/javascripts\//, loader: 'imports-loader?jQuery=jquery' }
        ]
    }
};
