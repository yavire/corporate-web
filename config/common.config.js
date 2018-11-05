// Plugins
const Webpack = require('webpack');
const Path = require('path')
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');

// Options
const opts = {
  rootDir: process.cwd(),
  devBuild: process.env.NODE_ENV !== 'production',
};

module.exports = {
  entry: {
    app: './src/js/main'
  },
  output: {
    path: Path.join(opts.rootDir, 'dist'),
    pathinfo: opts.devBuild,
    publicPath: "dist",
    filename: 'js/bundle.js'
  },
  plugins: [
    // Extract css files to seperate bundle
    new ExtractTextPlugin('css/robust.css'),
    // Copy fonts to dist
    new CopyWebpackPlugin([
      {from: 'src/fonts', to: 'fonts'}
    ]),
    // Copy dist folders to docs/dist (for demo pages)
    new CopyWebpackPlugin([
      {from: 'dist', to: '../docs/dist'}
    ]),
    // jQuery and PopperJS
    new Webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      Popper: ['popper.js', 'default']
    })
  ],
  module: {
    rules: [
      // Babel-loader
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        loader: ['babel-loader']
      },
      // Css-loader & sass-loader
      {
        test: /\.scss$/,
        loader: ExtractTextPlugin.extract(
          {
            fallback: ['style-loader'],
            use: [
              // Translates CSS into CommonJS modules
              'css-loader',
              // Run postcss actions
              {
                loader: 'postcss-loader',
                options: {
                  ident: 'postcss',
                  plugins: function () {
                    return [
                      require('precss'),
                      require('autoprefixer')
                    ];
                  }
                }
              },
              // Compiles SASS to CSS
              'sass-loader'
            ]
          }
        )
      },
      // Load fonts
      {
        test   : /\.(ttf|eot|svg|woff(2)?)(\?[a-z0-9=&.]+)?$/,
        loader : 'file-loader',
        options: {
          name: "/../../fonts/[name].[ext]",
        }
      }
    ]
  },
  resolve: {
    extensions: ['.js', '.scss'],
    modules: ['node_modules'],
    alias: {
      request$: 'xhr'
    }
  }
}
