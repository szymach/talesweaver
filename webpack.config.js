const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ClearWebpackPlugin = require('clean-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { styles } = require('@ckeditor/ckeditor5-dev-utils');
const path = require('path');

module.exports = {
    mode: 'production',
    entry: {
        main: [
            path.resolve(__dirname, "node_modules/font-awesome/scss/font-awesome.scss"),
            "bootstrap-loader",
            "./assets/typescript/app.ts"
        ],
        ckeditor: [
            "./assets/scss/ckeditor.scss",
            "./assets/js/ckeditor.js",
            path.resolve(__dirname, 'assets/js/ckeditor/pl.js')
        ],
        display: "./assets/typescript/displayOnly.ts"
    },
    devtool: 'cheap-module-eval-source-map',
    output: {
        path: path.resolve(__dirname, 'public/assets'),
        filename: '[name].js',
        pathinfo: false
    },
    resolve: {
        extensions: ['.js', ".ts"],
        alias: {
            ckeditor: path.resolve(__dirname, 'assets/js/ckeditor/ckeditor.js')
        }
    },
    plugins: [
        new MiniCssExtractPlugin({ filename: '[name].css' }),
        new OptimizeCssAssetsPlugin(),
        new ClearWebpackPlugin(['public/assets'], { root: __dirname, verbose: true })
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
            new TerserPlugin({
                parallel: true,
                cache: true,
                terserOptions: {
                    mangle: false,
                    warnings: false,
                    ecma: 6
                }
            })
        ]
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
                            importLoaders: 2
                        }
                    },
                    'sass-loader'
                ]
            },
            {
                test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                use: [{ loader: 'file-loader', options: { publicPath: '/assets/' } }]
            },
            {
                test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
                exclude: [path.resolve(__dirname, "node_modules/@ckeditor")],
                use: ['file-loader']
            },
            { test: /ckeditor5-[^/]+\/theme\/icons\/[^/]+\.svg$/, use: ['raw-loader'] }
        ]
    },
    performance: { hints: false },
    stats: {
        assets: false,
        cachedAssets: false,
        builtAt: false,
        cached: false,
        children: false,
        chunks: true,
        chunkGroups: false,
        chunkModules: false,
        modules: false,
        moduleTrace: false,
        providedExports: false,
        reasons: false,
        timings: false
    }
};
