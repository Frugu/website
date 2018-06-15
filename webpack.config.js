var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning()
    .enableSassLoader()
    .enableBuildNotifications()

    .addEntry('app', './assets/js/app.js')
;

module.exports = Encore.getWebpackConfig();
