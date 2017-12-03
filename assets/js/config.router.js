'use strict';

/**
 * Config for the router
 */
angular.module('app')
  .run(
    [          '$rootScope', '$state', '$stateParams',
      function ($rootScope,   $state,   $stateParams) {
          $rootScope.$state = $state;
          $rootScope.$stateParams = $stateParams;        
      }
    ]
  )
  .config(
    [          '$stateProvider', '$urlRouterProvider',
      function ($stateProvider,   $urlRouterProvider) {
          
          $urlRouterProvider.otherwise('/app/dashboard-v1');
          
          $stateProvider
              // routing bawaan
              .state('app', {
                  abstract: true,
                  url: '/app',
                  templateUrl: 'app/components/app/app.html'
              })              
              .state('app.dashboard-v1', {
                  url: '/dashboard-v1',
                  //templateUrl: 'assets/tpl/app_dashboard_v1.html',
                  templateUrl: 'app/components/dashboard/dashboard.html',
                  resolve: {
                    deps: ['$ocLazyLoad',
                      function( $ocLazyLoad ){
                        return $ocLazyLoad.load(['assets/js/controllers/chart.js']);
                    }]
                  }
              })
              .state('app.dashboard-v2', {
                  url: '/dashboard-v2',
                  templateUrl: 'assets/tpl/app_dashboard_v2.html',
                  resolve: {
                    deps: ['$ocLazyLoad',
                      function( $ocLazyLoad ){
                        return $ocLazyLoad.load(['assets/js/controllers/chart.js']);
                    }]
                  }
              })
              .state('app.ui', {
                  url: '/ui',
                  template: '<div ui-view class="fade-in-up"></div>'
              })
              .state('app.ui.buttons', {
                  url: '/buttons',
                  templateUrl: 'assets/tpl/ui_buttons.html'
              })
              .state('app.ui.icons', {
                  url: '/icons',
                  templateUrl: 'assets/tpl/ui_icons.html'
              })
              .state('app.ui.grid', {
                  url: '/grid',
                  templateUrl: 'assets/tpl/ui_grid.html'
              })
              .state('app.ui.widgets', {
                  url: '/widgets',
                  templateUrl: 'assets/tpl/ui_widgets.html'
              })          
              .state('app.ui.bootstrap', {
                  url: '/bootstrap',
                  templateUrl: 'assets/tpl/ui_bootstrap.html'
              })
              .state('app.ui.sortable', {
                  url: '/sortable',
                  templateUrl: 'assets/tpl/ui_sortable.html'
              })
              .state('app.ui.portlet', {
                  url: '/portlet',
                  templateUrl: 'assets/tpl/ui_portlet.html'
              })
              .state('app.ui.timeline', {
                  url: '/timeline',
                  templateUrl: 'assets/tpl/ui_timeline.html'
              })
              .state('app.ui.tree', {
                  url: '/tree',
                  templateUrl: 'assets/tpl/ui_tree.html',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad ){
                          return $ocLazyLoad.load('angularBootstrapNavTree').then(
                              function(){
                                 return $ocLazyLoad.load('assets/js/controllers/tree.js');
                              }
                          );
                        }
                      ]
                  }
              })
              .state('app.ui.toaster', {
                  url: '/toaster',
                  templateUrl: 'assets/tpl/ui_toaster.html',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load('toaster').then(
                              function(){
                                 return $ocLazyLoad.load('assets/js/controllers/toaster.js');
                              }
                          );
                      }]
                  }
              })
              .state('app.ui.jvectormap', {
                  url: '/jvectormap',
                  templateUrl: 'assets/tpl/ui_jvectormap.html',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load('assets/js/controllers/vectormap.js');
                      }]
                  }
              })
              .state('app.ui.googlemap', {
                  url: '/googlemap',
                  templateUrl: 'assets/tpl/ui_googlemap.html',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( [
                            'assets/js/app/map/load-google-maps.js',
                            'assets/js/app/map/ui-map.js',
                            'assets/js/app/map/map.js'] ).then(
                              function(){
                                return loadGoogleMaps(); 
                              }
                            );
                      }]
                  }
              })
              .state('app.chart', {
                  url: '/chart',
                  templateUrl: 'assets/tpl/ui_chart.html',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('assets/js/controllers/chart.js');
                      }]
                  }
              })
              // table
              .state('app.table', {
                  url: '/table',
                  template: '<div ui-view></div>'
              })
              .state('app.table.static', {
                  url: '/static',
                  templateUrl: 'assets/tpl/table_static.html'
              })
              .state('app.table.datatable', {
                  url: '/datatable',
                  templateUrl: 'assets/tpl/table_datatable.html'
              })
              .state('app.table.footable', {
                  url: '/footable',
                  templateUrl: 'assets/tpl/table_footable.html'
              })
              .state('app.table.grid', {
                  url: '/grid',
                  templateUrl: 'assets/tpl/table_grid.html',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad ){
                          return $ocLazyLoad.load('ngGrid').then(
                              function(){
                                  return $ocLazyLoad.load('assets/js/controllers/grid.js');
                              }
                          );
                      }]
                  }
              })
              // form
              .state('app.form', {
                  url: '/form',
                  template: '<div ui-view class="fade-in"></div>',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('assets/js/controllers/form.js');
                      }]
                  }
              })
              .state('app.form.elements', {
                  url: '/elements',
                  templateUrl: 'assets/tpl/form_elements.html'
              })
              .state('app.form.validation', {
                  url: '/validation',
                  templateUrl: 'assets/tpl/form_validation.html'
              })
              .state('app.form.wizard', {
                  url: '/wizard',
                  templateUrl: 'assets/tpl/form_wizard.html'
              })
              .state('app.form.step', {
                  url: '/step',
                  templateUrl: 'assets/tpl/form_step_wizard.html',
                  resolve: {
                 	  deps: ['uiLoad',
                        function(uiLoad){
                          return uiLoad.load('assets/css/form-step-wizard.css');
                      }]
                  }
              })
              .state('app.form.fileupload', {
                  url: '/fileupload',
                  templateUrl: 'assets/tpl/form_fileupload.html',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load('angularFileUpload').then(
                              function(){
                                 return $ocLazyLoad.load('assets/js/controllers/file-upload.js');
                              }
                          );
                      }]
                  }
              })
              .state('app.form.imagecrop', {
                  url: '/imagecrop',
                  templateUrl: 'assets/tpl/form_imagecrop.html',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load('ngImgCrop').then(
                              function(){
                                 return $ocLazyLoad.load('assets/js/controllers/imgcrop.js');
                              }
                          );
                      }]
                  }
              })
              .state('app.form.select', {
                  url: '/select',
                  templateUrl: 'assets/tpl/form_select.html',
                  controller: 'SelectCtrl',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad ){
                          return $ocLazyLoad.load('ui.select').then(
                              function(){
                                  return $ocLazyLoad.load('assets/js/controllers/select.js');
                              }
                          );
                      }]
                  }
              })
              .state('app.form.slider', {
                  url: '/slider',
                  templateUrl: 'assets/tpl/form_slider.html',
                  controller: 'SliderCtrl',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad ){
                          return $ocLazyLoad.load('vr.directives.slider').then(
                              function(){
                                  return $ocLazyLoad.load('assets/js/controllers/slider.js');
                              }
                          );
                      }]
                  }
              })
              .state('app.form.editor', {
                  url: '/editor',
                  templateUrl: 'assets/tpl/form_editor.html',
                  controller: 'EditorCtrl',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad ){
                          return $ocLazyLoad.load('textAngular').then(
                              function(){
                                  return $ocLazyLoad.load('assets/js/controllers/editor.js');
                              }
                          );
                      }]
                  }
              })
              // pages
              .state('app.page', {
                  url: '/page',
                  template: '<div ui-view class="fade-in-down"></div>'
              })
              .state('app.page.prosedur-layanan', {
                  url: '/prosedur-layanan',
                  templateUrl: 'assets/tpl/page_search.html'
              })
              .state('app.page.blog', {
                  url: '/blog',
                  templateUrl: 'blog/page_post.html'
              })
              
              .state('app.page.profile', {
                  url: '/profile',
                  templateUrl: 'assets/tpl/page_profile.html'
              })
              .state('app.page.post', {
                  url: '/post',
                  templateUrl: 'assets/tpl/page_post.html'
              })
              .state('app.page.search', {
                  url: '/search',
                  templateUrl: 'assets/tpl/page_search.html'
              })
              .state('app.page.invoice', {
                  url: '/invoice',
                  templateUrl: 'assets/tpl/page_invoice.html'
              })
              .state('app.page.price', {
                  url: '/price',
                  templateUrl: 'assets/tpl/page_price.html'
              })
              .state('app.docs', {
                  url: '/docs',
                  templateUrl: 'assets/tpl/docs.html'
              })
              // others
              .state('lockme', {
                  url: '/lockme',
                  templateUrl: 'assets/tpl/page_lockme.html'
              })
              .state('access', {
                  url: '/access',
                  template: '<div ui-view class="fade-in-right-big smooth"></div>'
              })
              .state('access.signin', {
                  url: '/signin',
                  templateUrl: 'assets/tpl/page_signin.html',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/controllers/signin.js'] );
                      }]
                  }
              })
              .state('access.signup', {
                  url: '/signup',
                  templateUrl: 'assets/tpl/page_signup.html',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/controllers/signup.js'] );
                      }]
                  }
              })
              .state('access.forgotpwd', {
                  url: '/forgotpwd',
                  templateUrl: 'assets/tpl/page_forgotpwd.html'
              })
              .state('access.404', {
                  url: '/404',
                  templateUrl: 'assets/tpl/page_404.html'
              })

              // fullCalendar
              .state('app.calendar', {
                  url: '/calendar',
                  templateUrl: 'assets/tpl/app_calendar.html',
                  // use resolve to load other dependences
                  resolve: {
                      deps: ['$ocLazyLoad', 'uiLoad',
                        function( $ocLazyLoad, uiLoad ){
                          return uiLoad.load(
                            ['assets/vendor/jquery/fullcalendar/fullcalendar.css',
                              'assets/vendor/jquery/fullcalendar/theme.css',
                              'assets/vendor/jquery/jquery-ui-1.10.3.custom.min.js',
                              'assets/vendor/libs/moment.min.js',
                              'assets/vendor/jquery/fullcalendar/fullcalendar.min.js',
                              'assets/js/app/calendar/calendar.js']
                          ).then(
                            function(){
                              return $ocLazyLoad.load('ui.calendar');
                            }
                          )
                      }]
                  }
              })

              // mail
              .state('app.mail', {
                  abstract: true,
                  url: '/mail',
                  templateUrl: 'assets/tpl/mail.html',
                  // use resolve to load other dependences
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/app/mail/mail.js',
                                               'assets/js/app/mail/mail-service.js',
                                               'assets/vendor/libs/moment.min.js'] );
                      }]
                  }
              })
              .state('app.mail.list', {
                  url: '/inbox/{fold}',
                  templateUrl: 'assets/tpl/mail.list.html'
              })
              .state('app.mail.detail', {
                  url: '/{mailId:[0-9]{1,4}}',
                  templateUrl: 'assets/tpl/mail.detail.html'
              })
              .state('app.mail.compose', {
                  url: '/compose',
                  templateUrl: 'assets/tpl/mail.new.html'
              })

              .state('layout', {
                  abstract: true,
                  url: '/layout',
                  templateUrl: 'assets/tpl/layout.html'
              })
              .state('layout.fullwidth', {
                  url: '/fullwidth',
                  views: {
                      '': {
                          templateUrl: 'assets/tpl/layout_fullwidth.html'
                      },
                      'footer': {
                          templateUrl: 'assets/tpl/layout_footer_fullwidth.html'
                      }
                  },
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/controllers/vectormap.js'] );
                      }]
                  }
              })
              .state('layout.mobile', {
                  url: '/mobile',
                  views: {
                      '': {
                          templateUrl: 'assets/tpl/layout_mobile.html'
                      },
                      'footer': {
                          templateUrl: 'assets/tpl/layout_footer_mobile.html'
                      }
                  }
              })
              .state('layout.app', {
                  url: '/app',
                  views: {
                      '': {
                          templateUrl: 'assets/tpl/layout_app.html'
                      },
                      'footer': {
                          templateUrl: 'assets/tpl/layout_footer_fullwidth.html'
                      }
                  },
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/controllers/tab.js'] );
                      }]
                  }
              })
              .state('apps', {
                  abstract: true,
                  url: '/apps',
                  templateUrl: 'assets/tpl/layout.html'
              })
              .state('apps.note', {
                  url: '/note',
                  templateUrl: 'assets/tpl/apps_note.html',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/app/note/note.js',
                                               'assets/vendor/libs/moment.min.js'] );
                      }]
                  }
              })
              .state('apps.contact', {
                  url: '/contact',
                  templateUrl: 'assets/tpl/apps_contact.html',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/app/contact/contact.js'] );
                      }]
                  }
              })
              .state('app.weather', {
                  url: '/weather',
                  templateUrl: 'assets/tpl/apps_weather.html',
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad ){
                          return $ocLazyLoad.load(
                              {
                                  name: 'angular-skycons',
                                  files: ['assets/js/app/weather/skycons.js',
                                          'assets/vendor/libs/moment.min.js', 
                                          'assets/js/app/weather/angular-skycons.js',
                                          'assets/js/app/weather/ctrl.js' ] 
                              }
                          );
                      }]
                  }
              })
	          /** HMW-Framework **/
	          .state('hmw', {
                  abstract: true,
                  url: '/hmw',
                  templateUrl: 'app/components/app/app.html'
              })
              // browse product
              .state('hmw.browse', {
                  abstract: true,
                  url: '^/c',
                  templateUrl: 'assets/tpl/browse/browse.html',
                  // use resolve to load other dependences
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['assets/js/app/browse/browse.js',
                                               'assets/js/app/browse/browse-service.js'] );
                      }]
                  }
              })
              .state('hmw.browse.list', {
                  url: '/{ctrl}/{fold}',
                  templateUrl: 'assets/tpl/browse/browse.list.html'
              })
              .state('hmw.browse.detail', {
                  url: '/{ctrl}/{itemId:[0-9]{1,4}}',
                  templateUrl: 'assets/tpl/browse/browse.detail.html'
              })
              // blogs
              .state('hmw.blog', {
                  url: '/blog',
                  template: '<div ui-view class="fade-in-down"></div>'
              })
              .state('hmw.blog.post', {
                  url: '/post',
                  templateUrl: 'app/components/blog/page_post.html'
              })
              .state('hmw.profile', {
              	  url: '^/profile.html?id&tipe',
                  templateUrl:  function ($stateParams) {
                  	  return 'profile.html?id=' + $stateParams.id + ' &tipe=' + $stateParams.tipe;
                  },
                  resolve: {
                  	  deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load('toaster').then(
                              function(){
                                 return $ocLazyLoad.load([
		                          	'app/components/base-common/commonCtrl.js',
		                          	'app/components/base-common/commonService.js'
		                         ]);
                              }
                          );
                      }]
                  }
              })
              // common ctrl
              .state('hmw.ctrl', {
                  url: '^/{ctrl}',
                  template: '<div ui-view></div>',
        				  resolve: {
        				     ctrl: ['$stateParams', function($stateParams){
        				         return $stateParams.ctrl;
        				     }]
        				  }
              })
              .state('hmw.ctrl.func', {
                  url: '/{func}',
                  templateUrl:  function ($stateParams) {
                  	  return 'app/components/' + $stateParams.ctrl +'/' + $stateParams.ctrl + '_' + $stateParams.func + '.html';
                  },
              	  ctrl: ['$stateParams', function($stateParams){
      			         return $stateParams.ctrl;
      			      }],
      			      func: ['$stateParams', function($stateParams){
      			         return $stateParams.func;
      			      }],
                  resolve: {
                  	  deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load('toaster').then(
                              function(){
                                 return $ocLazyLoad.load([
                                    // 'app/components/pilihan/pilihanService.js',
                                    'app/components/pilihan/pilihanCtrl.js',
                                  	'app/components/base-common/commonCtrl.js',
		                          	'app/components/base-common/commonService.js'
		                         ]);
                              }
                          );
                      }]
                  }
              })
              .state('hmw.ctrl.form', {
                  url: '/{func}/{id}',
                  templateUrl:  function ($stateParams) {
                    return 'app/components/' + $stateParams.ctrl +'/' + $stateParams.ctrl + '_' + $stateParams.func + '.html';
                  },
                  ctrl: ['$stateParams', function($stateParams){
                     return $stateParams.ctrl;
                  }],
                  func: ['$stateParams', function($stateParams){
                     return $stateParams.func;
                  }],
                  id: ['$stateParams', function($stateParams){
                     return $stateParams.id;
                  }],
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load('toaster').then(
                              function(){
                                 return $ocLazyLoad.load([
                                'app/components/' + $stateParams.ctrl +'/' + $stateParams.ctrl + 'Service.js',
                                'app/components/' + $stateParams.ctrl +'/' + $stateParams.ctrl + 'Ctrl.js',    
                                'app/components/base-common/commonCtrl.js',
                                'app/components/base-common/commonService.js'
                             ]);
                              }
                          );
                      }]
                  }
              })
      }
    ]
  );