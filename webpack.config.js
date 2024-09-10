require('dotenv').config();

const Encore = require('@symfony/webpack-encore');
const glob = require('glob');
const fs = require('fs');
const path = require('path');
const CopyPlugin = require('copy-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const chokidar = require('chokidar');
const CleanTerminalPlugin = require('clean-terminal-webpack-plugin');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const blocks = glob
  .sync('./web/app/themes/theme/blocks/**/block.json')
  .reduce((acc, curr) => {
    const json = JSON.parse(
      fs.readFileSync(path.join(__dirname, curr), 'utf8')
    );

    const base = curr.replace('block.json', '');

    const properties = [
      'editorScript',
      'editorStyle',
      'style',
      'render',
      'viewScript',
    ];

    const block = {
      entries: {},
      files: [
        {
          from: path.join(__dirname, curr),
          to: `blocks/${json.name}/block.json`,
          transform(content) {
            const blockJSON = JSON.parse(content);

            properties.forEach((property) => {
              if (blockJSON[property] && blockJSON[property].includes('scss')) {
                blockJSON[property] = `file:${blockJSON[property]
                  .replace('file:', '')
                  .replace('scss', 'css')}`;
              }
            });

            return JSON.stringify(blockJSON);
          },
        },
      ],
    };

    properties.forEach((property) => {
      if (json[property]) {
        if (
          json[property].indexOf('.php') > -1 ||
          json[property].indexOf('.twig') > -1
        ) {
          block.files.push({
            from: path.join(
              __dirname,
              base,
              json[property].replace('file:', '')
            ),
            to: path.join(
              `blocks/${json.name}`,
              json[property].replace('file:', '')
            ),
          });
        } else {
          block.entries[
            `blocks/${json.name}/${json[property].split('./')[1].split('.')[0]}`
          ] = path.join(__dirname, base, json[property].replace('file:', ''));
        }
      }
    });

    return {
      ...acc,
      [json.name]: block,
    };
  }, {});

Encore
  // directory where compiled assets will be stored
  .setOutputPath('./web/app/themes/theme/public/')
  // public path used by the web server to access the output path
  .setPublicPath('/app/themes/theme/public')
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')

  /*
   * ENTRY CONFIG
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
   */
  .addEntry('app', './web/app/themes/theme/assets/js/app.js')

  // Add blocks entries
  .addEntries(
    Object.values(blocks)
      .map((block) => block.entries)
      .reduce(
        (acc, curr) => ({
          ...acc,
          ...curr,
        }),
        {}
      )
  )

  // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
  // .enableStimulusBridge('./assets/controllers.json')

  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  // .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  // .enableSingleRuntimeChunk()
  .disableSingleRuntimeChunk()

  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  // .enableVersioning(Encore.isProduction())

  .configureBabel((config) => {
    config.plugins.push('@babel/plugin-transform-class-properties');
  })

  // enables @babel/preset-env polyfills
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = 3;
  })

  // enables Sass/SCSS support
  .enableSassLoader()
  .enablePostCssLoader()
  // uncomment if you use TypeScript
  //.enableTypeScriptLoader()

  // uncomment if you use React
  .enableReactPreset()

  .addPlugin(
    Object.keys(blocks).length
      ? new CopyPlugin({
          patterns: Object.values(blocks)
            .map((block) => block.files)
            .flat(),
        })
      : { apply: () => {} }
  )

  .addPlugin(new DependencyExtractionWebpackPlugin())

  .configureDevServerOptions((options) => {
    options.proxy = {
      '/': {
        index: '',
        context: () => true,
        target: process.env.WP_HOME,
        changeOrigin: true,
        secure: false,
      },
    };

    options.setupMiddlewares = (middlewares, devServer) => {
      const watcher = chokidar.watch([
        './web/app/themes/theme/**/*.twig',
        './web/app/themes/theme/**/*.php',
      ]);

      watcher.on('all', function () {
        for (const ws of devServer.webSocketServer.clients) {
          ws.send('{"type": "static-changed"}');
        }
      });

      return middlewares;
    };

    options.allowedHosts = process.env.WP_HOME;
    options.hot = true;
    options.devMiddleware = { writeToDisk: true };
    options.watchFiles = [
      './web/app/themes/theme/assets/**/*.scss',
      './web/app/themes/theme/assets/**/*.js',
    ];
  })

  .addPlugin(new CleanTerminalPlugin());

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

module.exports = Encore.getWebpackConfig();
