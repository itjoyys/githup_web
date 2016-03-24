// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('PK', ['ionic', 'starter.controllers'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

    .state('app', {
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'PKCtrl'
  })

   
.state('app.Football', {
    url: '/Football',
    views: {
      'menuContent': {
        templateUrl: 'templates/football.html',
        controller: 'Football'
      }
    }
  })
.state('app.Basketball', {
    url: '/Basketball',
    views: {
      'menuContent': {
        templateUrl: 'templates/football.html',
        controller: 'Basketball'
      }
    }
  })
.state('app.Volleyball', {
    url: '/Volleyball',
    views: {
      'menuContent': {
        templateUrl: 'templates/football.html',
        controller: 'Volleyball'
      }
    }
  })
.state('app.Tennis', {
    url: '/Tennis',
    views: {
      'menuContent': {
        templateUrl: 'templates/football.html',
        controller: 'Tennis'
      }
    }
  })
.state('app.Baseball', {
    url: '/Baseball',
    views: {
      'menuContent': {
        templateUrl: 'templates/football.html',
        controller: 'Baseball'
      }
    }
  })

.state('app.matchnamelist', {
    url: '/matchnamelist/:sporttype/:theurl/:match/:oddtype',
    views: {
      'menuContent': {
        templateUrl: 'templates/matchnamelist.html',
        controller: 'matchnamelistctrl'
      }
    }
  })
.state('app.ShowBetList', {
    url: '/ShowBetList',
    views: {
      'menuContent': {
        templateUrl: 'templates/ShowBetList.html',
        controller: 'ShowBetList'
      }
    }
  })

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/Football');
});
