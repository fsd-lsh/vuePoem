'use strict'
const utils = require('./utils');
const webpack = require('webpack');
const config = require('../config');
const {merge} = require('webpack-merge');
const path = require('path');
const baseWebpackConfig = require('./webpack.base.conf');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const portfinder = require('portfinder');
const ini = require('ini');
const fs = require('fs');

let str = fs.readFileSync(path.resolve(__dirname, '../../.env')).toString();
let env = ini.parse(str);
const VUE_HOST = env.VUE_HOST;
const VUE_PORT = env.VUE_PORT && Number(env.VUE_PORT);
const PHP_HOST = env.PHP_HOST;
const PHP_PORT = env.PHP_PORT && Number(env.PHP_PORT);

const devWebpackConfig = merge(baseWebpackConfig, {
    module: {
        rules: utils.styleLoaders({sourceMap: config.dev.cssSourceMap, usePostCSS: true})
    },
    // cheap-module-eval-source-map is faster for development
    devtool: config.dev.devtool,

    // these devServer options should be customized in /config/index.js
    devServer: {
        client: {
            logging: 'warn',
            overlay: config.dev.errorOverlay
                ? { warnings: false, errors: true }
                : false,
            progress: true,
        },
        static: false,
        devMiddleware: {
            publicPath: config.dev.assetsPublicPath
        },
        historyApiFallback: {
            rewrites: [
                {from: /.*/, to: path.posix.join(config.dev.assetsPublicPath, 'index.html')},
            ],
        },
        hot: true,
        compress: true,
        host: VUE_HOST || config.dev.host,
        port: VUE_PORT || config.dev.port,
        open: config.dev.autoOpenBrowser,
        proxy: {
            '/admin': {
                target: 'http://' + env.PHP_HOST + ':' + env.PHP_PORT,
                changeOrigin: true,
                pathRewrite: {
                    '^/admin': '/admin'
                },
                secure: false,
            }
        },
        allowedHosts: "all",
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': require('../config/dev.env')
        }),
        new webpack.HotModuleReplacementPlugin(),
        new webpack.NoEmitOnErrorsPlugin(),
        // https://github.com/ampedandwired/html-webpack-plugin
        new HtmlWebpackPlugin({
            filename: 'index.html',
            template: 'index.html',
            favicon: './static/favicon.ico',
            inject: true
        }),
        // copy custom static assets
        new CopyWebpackPlugin({
            patterns:[{
                from: path.resolve(__dirname, '../static'),
                to: config.dev.assetsSubDirectory,
                globOptions: {            // webpack5 ignore要写在globOptions这里
                    ignore: ['.*']
                }
            }]
        }),
    ],
    optimization: {
        moduleIds: 'named' // webpack5 采用此方式代替 NamedModulesPlugin
    },
})

module.exports = new Promise((resolve, reject) => {

    portfinder.basePort = process.env.PORT || config.dev.port
    portfinder.getPort((err, port) => {

        if (err) {
            reject(err)
        } else {
            // publish the new Port, necessary for e2e tests
            process.env.PORT = port
            // add port to devServer config
            devWebpackConfig.devServer.port = port

            resolve(devWebpackConfig)
        }
    })
})
