module.exports = {
    entry: {
        'app.js': "./assets/js",
        css: ''
    },
    output: {
        path: 'web/js',
        filename: "app.js"
    },
    module: {
        loaders: [
            { test: /\.scss$/, loader: "style!css!sass" },
            { test: /app\.js$/, loader: "uglify" }
        ]
    }
};
