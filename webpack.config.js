const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ClearWebpackPlugin = require('clean-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');
const { styles } = require( '@ckeditor/ckeditor5-dev-utils' );
const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        main: [
            path.resolve(__dirname, "node_modules/font-awesome/scss/font-awesome.scss"),
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
            bootstrap: path.resolve(__dirname, 'node_modules/bootstrap-sass')
        }
    },
    plugins: [
        new webpack.ProvidePlugin({ $: 'jquery', jQuery: 'jquery' }),
        new MiniCssExtractPlugin({ filename: '[name].css' }),
        new OptimizeCssAssetsPlugin(),
        new CKEditorWebpackPlugin({ language: 'pl' }),
        new ClearWebpackPlugin(['public/assets'], { root: __dirname, verbose: true }),
    ],
    optimization: {
        minimizer: [
            new OptimizeCssAssetsPlugin({
                cssProcessorOptions: {
                    discardComments: {
                        removeAll: true
                    }
                }
            }),
            new UglifyJsPlugin({
                parallel: true,
                sourceMap: true,
                cache: true,
                uglifyOptions: {
                    mangle: false,
                    warnings: false,
                    ecma: 6,
                    sourceMap: true,
                    compress: {
                        // https://github.com/mishoo/UglifyJS2/tree/harmony#compress-options
                        arrows: false,
                        booleans: false,
                        collapse_vars: false,
                        comparisons: false,
                        computed_props: false,
                        conditionals: true,
                        dead_code: true,
                        evaluate: true,
                        hoist_funs: false,
                        hoist_props: false,
                        hoist_vars: false,
                        if_return: false,
                        inline: false,
                        join_vars: false,
                        keep_infinity: true,
                        loops: false,
                        negate_iife: false,
                        properties: false,
                        reduce_funcs: false,
                        reduce_vars: false,
                        sequences: false,
                        side_effects: false,
                        switches: false,
                        top_retain: false,
                        toplevel: false,
                        typeofs: false,
                        unused: false,
                    },
                }
            })
        ],
        splitChunks: {
            cacheGroups: {
                js: {
                    test: /\.js$/,
                    name: 'common',
                    chunks: "all",
                    minChunks: 2,
                },
                css: {
                    test: /\.(scss|css)$/,
                    name: 'common',
                    chunks: 'all',
                    minChunks: 2,
                }
            }
        },
    },
    module: {
        rules: [
            { test: /\.ts$/, use: ["awesome-typescript-loader"] },
            {
                test: /\.scss$/,
                use: [
                    { loader: MiniCssExtractPlugin.loader },
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true,
                            importLoaders: 2
                        }
                    },
                    {
                        loader: 'postcss-loader',
                        options: { sourceMap: true }
                    },
                    {
                        loader: 'sass-loader',
                        options: { sourceMap: true }
                    }
                ]
            },
            {
                test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                use: [{ loader: 'file-loader', options: { publicPath: '/assets/' } }]
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
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'postcss-loader',
                        options: styles.getPostCssConfig( {
                            themeImporter: {
                                themePath: require.resolve( '@ckeditor/ckeditor5-theme-lark' )
                            },
                            minify: true
                        } )
                    }
                ]
            }
        ]
    },
    performance: { hints: false }
};
