const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ClearWebpackPlugin = require('clean-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');
const { styles } = require( '@ckeditor/ckeditor5-dev-utils' );
const path = require('path');

module.exports = {
    entry: {
        main: [
            "font-awesome-sass-loader",
            "bootstrap-loader",
            "./assets/scss/base.scss",
            "./assets/typescript/app.ts"
        ],
        ckeditor: ["./assets/scss/ckeditor.scss", "./assets/js/ckeditor.js"]
    },
    devtool: "source-map",
    output: {
        path: path.resolve(__dirname, 'public/assets'),
        filename: '[name].js'
    },
    resolve: {
        extensions: [ '.js', ".ts" ],
        alias: {
            'bootstrap': path.resolve(__dirname, 'node_modules/bootstrap-sass')
        }
    },
    plugins: [
        new webpack.ProvidePlugin({ $: 'jquery', jQuery: 'jquery' }),
        new ExtractTextPlugin({ filename: '[name].css', allChunks: true }),
        new OptimizeCssAssetsPlugin(),
        new CKEditorWebpackPlugin({ language: 'pl' }),
        new ClearWebpackPlugin([path.resolve(__dirname, 'public/assets')], { verbose: true }),
        new UglifyJsPlugin({ uglifyOptions: { mangle: false, warnings: false, ecma: 6, sourceMap: true }})
    ],
    module: {
        rules: [
            { test: /\.ts$/, loader: "awesome-typescript-loader" },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({ fallback: 'style-loader', use: ['css-loader', 'postcss-loader', 'sass-loader'] })
            },
            {
                test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                use: [{ loader: 'file-loader', query: { publicPath: '/assets/' } }]
            },
            {
                test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
                exclude: [path.resolve(__dirname, "node_modules/@ckeditor")],
                use: [ 'file-loader' ]
            },
            { test: /bootstrap-sass\/assets\/javascripts\// },
            { test: /ckeditor5-[^/]+\/theme\/icons\/[^/]+\.svg$/, use: [ 'raw-loader' ] },
            {
                test: /ckeditor5-[^/]+\/theme\/[\w-/]+\.css$/,
                use: [
                    { loader: 'style-loader', options: { singleton: true } },
                    {
                        loader: 'postcss-loader',
                        options: styles.getPostCssConfig({
                            themeImporter: { themePath: require.resolve('@ckeditor/ckeditor5-theme-lark') },
                            minify: true
                        })
                    }
                ]
            }
        ]
    },
    performance: { hints: false }
};
