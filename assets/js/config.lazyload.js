// lazyload config

angular.module('app')
    /**
   * jQuery plugin config use ui-jq directive , config the js and css files that required
   * key: function name of the jQuery plugin
   * value: array of the css js file located
   */
  .constant('JQ_CONFIG', {
      easyPieChart:   ['assets/vendor/jquery/charts/easypiechart/jquery.easy-pie-chart.js'],
      sparkline:      ['assets/vendor/jquery/charts/sparkline/jquery.sparkline.min.js'],
      plot:           ['assets/vendor/jquery/charts/flot/jquery.flot.min.js', 
                          'assets/vendor/jquery/charts/flot/jquery.flot.resize.js',
                          'assets/vendor/jquery/charts/flot/jquery.flot.tooltip.min.js',
                          'assets/vendor/jquery/charts/flot/jquery.flot.spline.js',
                          'assets/vendor/jquery/charts/flot/jquery.flot.orderBars.js',
                          'assets/vendor/jquery/charts/flot/jquery.flot.pie.min.js'],
      slimScroll:     ['assets/vendor/jquery/slimscroll/jquery.slimscroll.min.js'],
      sortable:       ['assets/vendor/jquery/sortable/jquery.sortable.js'],
      nestable:       ['assets/vendor/jquery/nestable/jquery.nestable.js',
                          'assets/vendor/jquery/nestable/nestable.css'],
      filestyle:      ['assets/vendor/jquery/file/bootstrap-filestyle.min.js'],
      slider:         ['assets/vendor/jquery/slider/bootstrap-slider.js',
                          'assets/vendor/jquery/slider/slider.css'],
      chosen:         ['assets/vendor/jquery/chosen/chosen.jquery.min.js',
                          'assets/vendor/jquery/chosen/chosen.css'],
      TouchSpin:      ['assets/vendor/jquery/spinner/jquery.bootstrap-touchspin.min.js',
                          'assets/vendor/jquery/spinner/jquery.bootstrap-touchspin.css'],
      wysiwyg:        ['assets/vendor/jquery/wysiwyg/bootstrap-wysiwyg.js',
                          'assets/vendor/jquery/wysiwyg/jquery.hotkeys.js'],
      dataTable:      ['assets/vendor/jquery/datatables/jquery.dataTables.min.js',
                          'assets/vendor/jquery/datatables/dataTables.bootstrap.js',
                          'assets/vendor/jquery/datatables/dataTables.bootstrap.css'],
      vectorMap:      ['assets/vendor/jquery/jvectormap/jquery-jvectormap.min.js', 
                          'assets/vendor/jquery/jvectormap/jquery-jvectormap-world-mill-en.js',
                          'assets/vendor/jquery/jvectormap/jquery-jvectormap-us-aea-en.js',
                          'assets/vendor/jquery/jvectormap/jquery-jvectormap.css'],
      footable:       ['assets/vendor/jquery/footable/footable.all.min.js',
                          'assets/vendor/jquery/footable/footable.core.css']
      }
  )
  // oclazyload config
  .config(['$ocLazyLoadProvider', function($ocLazyLoadProvider) {
      // We configure ocLazyLoad to use the lib script.js as the async loader
      $ocLazyLoadProvider.config({
          debug:  false,
          events: true,
          modules: [
              {
                  name: 'ngGrid',
                  files: [
                      'assets/vendor/modules/ng-grid/ng-grid.min.js',
                      'assets/vendor/modules/ng-grid/ng-grid.min.css',
                      'assets/vendor/modules/ng-grid/theme.css'
                  ]
              },
              {
                  name: 'ui.select',
                  files: [
                      'assets/vendor/modules/angular-ui-select/select.min.js',
                      'assets/vendor/modules/angular-ui-select/select.min.css'
                  ]
              },
              {
                  name:'angularFileUpload',
                  files: [
                    'assets/vendor/modules/angular-file-upload/angular-file-upload.min.js'
                  ]
              },
              {
                  name:'ui.calendar',
                  files: ['assets/vendor/modules/angular-ui-calendar/calendar.js']
              },
              {
                  name: 'ngImgCrop',
                  files: [
                      'assets/vendor/modules/ngImgCrop/ng-img-crop.js',
                      'assets/vendor/modules/ngImgCrop/ng-img-crop.css'
                  ]
              },
              {
                  name: 'angularBootstrapNavTree',
                  files: [
                      'assets/vendor/modules/angular-bootstrap-nav-tree/abn_tree_directive.js',
                      'assets/vendor/modules/angular-bootstrap-nav-tree/abn_tree.css'
                  ]
              },
              {
                  name: 'toaster',
                  files: [
                      'assets/vendor/modules/angularjs-toaster/toaster.min.js',
                      'assets/vendor/modules/angularjs-toaster/toaster.min.css'
                  ]
              },
              {
                  name: 'textAngular',
                  files: [
                      'assets/vendor/modules/textAngular/textAngular-sanitize.min.js',
                      'assets/vendor/modules/textAngular/textAngular.min.js'
                  ]
              },
              {
                  name: 'vr.directives.slider',
                  files: [
                      'assets/vendor/modules/angular-slider/angular-slider.min.js',
                      'assets/vendor/modules/angular-slider/angular-slider.css'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular',
                  files: [
                      'assets/vendor/modules/videogular/videogular.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.controls',
                  files: [
                      'assets/vendor/modules/videogular/plugins/controls.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.buffering',
                  files: [
                      'assets/vendor/modules/videogular/plugins/buffering.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.overlayplay',
                  files: [
                      'assets/vendor/modules/videogular/plugins/overlay-play.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.poster',
                  files: [
                      'assets/vendor/modules/videogular/plugins/poster.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.imaads',
                  files: [
                      'assets/vendor/modules/videogular/plugins/ima-ads.min.js'
                  ]
              }
          ]
      });
  }])
;