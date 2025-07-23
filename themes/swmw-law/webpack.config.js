const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');

module.exports = {
  mode: 'development',
  entry: {
    main: './assets/js/main.js',
    admin: './assets/js/admin.js',
    style: './assets/scss/style.scss',
    editor: './assets/scss/editor.scss',
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'js/[name].js',
    clean: true,
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env', '@babel/preset-react'],
          },
        },
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader',
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/[name].css',
    }),
    new BrowserSyncPlugin(
      {
        host: 'localhost',
        port: 3000,
        proxy: 'http://swmw-law.local',
        files: ['**/*.php', 'dist/css/**/*.css', 'dist/js/**/*.js'],
      },
      {
        reload: false,
      }
    ),
    new StylelintPlugin({
      files: 'assets/scss/**/*.scss',
      customSyntax: 'postcss-scss',
      configFile: path.resolve(__dirname, '.stylelintrc.json'),
      failOnError: false,
    }),
    new DependencyExtractionWebpackPlugin(),
  ],
  devtool: 'source-map',
}; 
