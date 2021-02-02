const CompressionPlugin = require("compression-webpack-plugin");

module.exports = {
    devServer: {
        proxy: {
            '/api': {
                target: 'https://thor-exhaust.tw/php/',
                secure: false,
                pathRewrite: {
                    '^/api': '/'
                }
            },
        }
    },
    filenameHashing: true,
    configureWebpack: () => {
        if (process.env.NODE_ENV === 'production') {
            return {
                plugins: [
                    new CompressionPlugin({
                        test: /\.js$|\.html$|\.css$/, // 需要压缩的文件类型
                        //|\.jpg$|\.jpeg$|\.png
                        threshold: 10240, // 归档需要进行压缩的文件大小最小值，我这个是10K以上的进行压缩
                        deleteOriginalAssets: false // 是否删除原文件
                    })
                ],
                optimization: {
                    runtimeChunk: 'single',
                    splitChunks: {
                        chunks: 'all',
                        maxInitialRequests: Infinity,
                        minSize: 20000,
                        cacheGroups: {
                            vendor: {
                                test: /[\\/]node_modules[\\/]/,
                                name(module) {
                                    // get the name. E.g. node_modules/packageName/not/this/part.js
                                    // or node_modules/packageName
                                    const packageName = module.context.match(/[\\/]node_modules[\\/](.*?)([\\/]|$)/)[1]
                                    // npm package names are URL-safe, but some servers don't like @ symbols
                                    return `npm.${packageName.replace('@', '')}`
                                }
                            }
                        }
                    }
                },
            }
        }


    },
}