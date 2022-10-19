'use strict'
const path = require('path');
const utils = require('./utils');
const webpack = require('webpack');
const config = require('../config');
const {merge} = require('webpack-merge');
const baseWebpackConfig = require('./webpack.base.conf');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerWebpackPlugin = require('css-minimizer-webpack-plugin');
const TerserWebpackPlugin = require("terser-webpack-plugin");
const os = require('os');

const env = require('../config/prod.env');

const webpackConfig = merge(baseWebpackConfig, {
    module: {
        rules: utils.styleLoaders({
            sourceMap: config.build.productionSourceMap,
            extract: true,
            usePostCSS: true
        })
    },
    devtool: config.build.productionSourceMap ? config.build.devtool : false,
    performance: {
        hints: false
    },
    output: {
        path: config.build.assetsRoot,
        filename: utils.assetsPath('js/[name].[chunkhash].js'),
        chunkFilename: utils.assetsPath('js/[id].[chunkhash].js')
    },
    plugins: [
        // http://vuejs.github.io/vue-loader/en/workflow/production.html
        new webpack.DefinePlugin({
            'process.env': env
        }),
        // extract css into its own file
        new MiniCssExtractPlugin({
            filename: utils.assetsPath('css/[name].css'),
            chunkFilename: utils.assetsPath('css/[name].[contenthash].css')
        }),
        // generate dist index.html with correct asset hash for caching.
        // you can customize output by editing /index.html
        // see https://github.com/ampedandwired/html-webpack-plugin
        new HtmlWebpackPlugin({
            filename: config.build.index,
            template: 'index.html',
            inject: true,
            favicon: './static/favicon.ico',
            minify: {
                removeComments: true,
                collapseWhitespace: true,
                removeAttributeQuotes: true
                // more options:
                // https://github.com/kangax/html-minifier#options-quick-reference
            },
            // necessary to consistently work with multiple chunks via CommonsChunkPlugin
            chunksSortMode: 'auto'
        }),
        // enable scope hoisting
        new webpack.optimize.ModuleConcatenationPlugin(),

        // copy custom static assets
        new CopyWebpackPlugin({
            patterns:[{
                from: path.resolve(__dirname, '../static'),
                to: config.build.assetsSubDirectory,
                globOptions: {            // webpack5 ignore要写在globOptions这里
                    ignore: ['.*']
                }
            }]
        }),
    ],
    optimization: {
        moduleIds: 'deterministic',
        runtimeChunk: {
            name: 'manifest'
        },
        minimize: true,
        minimizer: [
            new CssMinimizerWebpackPlugin(),
            new TerserWebpackPlugin({
                parallel: os.cpus().length,
            }),
        ],
        splitChunks: {
            chunks: 'all',
            minSize: 30000,
            minChunks: 1,
            automaticNameDelimiter: '~',
            cacheGroups: {
                vendors: {
                    name: 'vendors',
                    test({ resource }) {
                        return /[\\/]node_modules[\\/]/.test(resource);
                    },
                    priority: 10,
                },
                styles: {
                    name: "styles",
                    test: /\.(le|c)ss$/,
                    type: "css/mini-extract",
                    chunks: "all",
                    enforce: true,
                },
            },
        },
    }
})

if (config.build.productionGzip) {
    const CompressionWebpackPlugin = require('compression-webpack-plugin')

    webpackConfig.plugins.push(
        new CompressionWebpackPlugin({
            filename: '[path][base].gz',
            algorithm: 'gzip',
            test: new RegExp(
                '\\.(' +
                config.build.productionGzipExtensions.join('|') +
                ')$'
            ),
            threshold: 10240,
            minRatio: 0.8
        })
    )
}

if (config.build.bundleAnalyzerReport) {
    const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin
    webpackConfig.plugins.push(new BundleAnalyzerPlugin())
}

module.exports = webpackConfig
