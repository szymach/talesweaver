const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: [
        'font-awesome-sass-loader',
        'bootstrap-loader/extractStyles',
        "./assets/js/tags.js"
    ],
    output: {
        path: 'web/public',
        filename: "app.js"
    },
    resolve: { extensions: [ '', '.js' ] },
    plugins: [
        new ExtractTextPlugin('app.css', { allChunks: true })
    ],
    module: {
        loaders: [
            { test: /\.scss$/, loader: ExtractTextPlugin.extract('style', 'css!postcss!sass') },
            { test: /.*\/assets\/.*\.js$/, loader: "uglify" },
            { test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "url" },
            { test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/, loader: 'file' },
            { test: /bootstrap-sass\/assets\/javascripts\//, loader: 'imports?jQuery=jquery' },
        ]
    },
    'uglify-loader': {
        mangle: false
    }
};
