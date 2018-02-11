const ExtractTextPlugin = require('extract-text-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');
const path = require('path');

module.exports = {
    entry: [
        "font-awesome-sass-loader",
        "bootstrap-loader",
        "./assets/scss/base.scss",
        "./assets/scss/ckeditor.scss",
        "./assets/js/app.js",
        "./assets/js/sidemenu.js",
        "./assets/js/autosave.js",
        "./assets/js/ckeditor.js"
    ],
    output: {
        path: path.resolve(__dirname, 'public/assets'),
        filename: 'scripts.js'
    },
    resolve: {
        extensions: [ '.js' ]
    },
    plugins: [
        new ExtractTextPlugin({ filename: 'styles.css', allChunks: true }),
        new OptimizeCssAssetsPlugin(),
        new CKEditorWebpackPlugin({
            languages: ['pl', 'en'],
            language: 'pl'
        }),
//        new UglifyJsPlugin({
//            uglifyOptions: {
//                mangle: false,
//                warnings: false,
//                ecma: 8
//            }
//        })
    ],
    module: {
        rules: [
            { test: /\.scss$/, use: ExtractTextPlugin.extract({ fallback: 'style-loader', use: ['css-loader', 'postcss-loader', 'sass-loader'] }) },
            { test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/, use: ["url-loader"] },
            {
                test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
                exclude: [
                    path.resolve(__dirname, "node_modules/@ckeditor")
                ],
                use: ['file-loader']
            },
            { test: /bootstrap-sass\/assets\/javascripts\//, use: [ 'imports-loader?jQuery=jquery'] },
            {
                test: /\.svg$/,
                include: [
                    path.resolve(__dirname, "node_modules/@ckeditor")
                ],
                use: [ 'raw-loader' ]
            }
        ]
    },
    devtool: 'source-map',
    performance: { hints: false },
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    }
};
