angular.module('starter.controllers', [])

.controller('PKCtrl', function($scope, $ionicModal, $timeout,$http,$ionicLoading,$state,$location) {

   
  $scope.user={uid:'',token:'',username:''};
  $scope.makebet={sporttype:'',match_type:''};
  $scope.usermoney=0; 
  $scope.betData = {};
  $scope.db=null;
  $scope.sportname='';

  
  $ionicModal.fromTemplateUrl('templates/bet.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });
  $scope.gettoken=function(){
    $http({
        url: '/index.php/sports/go/',
        dataType: 'JSON',
        method: 'post',
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded; charset=UTF-8'
        }
      }).success(function(d) {
        $scope.user.uid=d.uid;
        $scope.user.token=d.token;
        $scope.islogin();
      }).error(function(error) {
        $ionicLoading.show({
          template: '获取数据失败'+error
        });
        $timeout(function() {
          $ionicLoading.hide();
        }, 2500);
        
      });
  }
  $scope.gettoken()
  $scope.islogin=function(){
    $http({
        url: '/index.php/sports/user/getuserinfo/',
        dataType: 'JSON',
        method: 'post',
        data:'uid='+$scope.user.uid+'&token='+$scope.user.token,
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded; charset=UTF-8'
        }
      }).success(function(d) {
        if(d.Success==0){
           $ionicLoading.show({ template: d.msg });
           
        }else $timeout(function() { $ionicLoading.hide(); }, 1500);
         $scope.usermoney=d.money
         $scope.username=d.username
      }).error(function(error) {
        $ionicLoading.show({
          template: '获取数据超时'
        });
        $timeout(function() {
          $ionicLoading.hide();
        }, 2500);
        
      });
      setTimeout(function(){$scope.islogin()},15000);
  }
  $scope.ShowBetList = function(){
    //console.log($location.url("/app/ShowBetList"));
    $location.url("/app/ShowBetList")
     //$state.go("Baseball"); 
  }
  $scope.closeshowbet = function() {
     
    $scope.modal.hide();
  };

   
  $scope.refreshshowbet=function(m){
    $scope.makebetshow($http,$ionicLoading,$scope,$scope.makebet.sporttype,$scope.makebet.match_type,$scope.betData['Match_ID[]'],m);
  }
  $scope.showbet = function(sporttype,match_type,matchid) {
    $scope.makebetshow($http,$ionicLoading,$scope,sporttype,match_type,matchid);
  };

  
  $scope.dopostbet = function() {
    console.log('Doing login', $scope.betData);
    obj=$scope.betData;
    pd=function(obj){  
                 var str = [];  
                 for(var p in obj){  
                   str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));  
                 }  
                 return str.join("&");  
               }
    $ionicLoading.show({
        template: '注单提交中...'
      });
    $http({
        url: '/index.php/sports/bet/post_bet/?dsorcg=1',
        dataType: 'JSON',
        method: 'post',
        data : pd(obj),
        timeout:5000,
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded; charset=UTF-8'
        }
      }).success(function(d) {
          if(d.login==2){
           $ionicLoading.show({ template: '请登录' });
           $timeout(function() { $ionicLoading.hide(); }, 1500);
        }else{
        $ionicLoading.show({ template: d.msg }); 
         
         if(d.status==0){
            if(d.data){
                d.data=d.data.data;
                betpk=Match_ShowType='';
                if(d.data.pk[2]=='QCRQ' || d.data.pk[2]=='SBRQ')betpk=d.data.pk.match_rgg;
                else if(d.data.pk[2]=='QCDX' || d.data.pk[2]=='SBDX')betpk=d.data.pk.match_dxgg;
                if (d.data.pk[2] == 'QCRQ') {
                    if (d.data.data[0]['Match_ShowType'] == 'H') {
                        PKINFO = '主让';
                        Match_ShowType = 'H';
                    } else {
                        PKINFO = '客让';
                        Match_ShowType = 'C';
                    }
                    
                }else  if (d.data.pk[2] == 'SBRQ') {
                    if (d.data.data[0]['Match_Hr_ShowType'] == 'H') {
                        PKINFO = '主让';
                        Match_ShowType = 'H';
                    } else {
                        PKINFO = '客让';
                        Match_ShowType = 'C';
                    } 
                }

                $scope.modal.show();
                $scope.betData.uid=$scope.user.uid;
                $scope.betData.token=$scope.user.token;
                $scope.betData.Match_Name=d.data.data[0]['Match_Name']
                $scope.betData.Match_Master=d.data.data[0]['Match_Master']
                $scope.betData.Match_Guest=d.data.data[0]['Match_Guest']
                $scope.betData.BetInfo=d.data.betinfo
                $scope.betData['Sport_Type[]']=$scope.makebet.sporttype
                $scope.betData['Match_ShowType[]']=Match_ShowType
                $scope.betData['Match_Type[]']=$scope.makebet.match_type
                $scope.betData['Match_ID[]']=d.data.data[0]['Match_ID']
                $scope.betData['Bet_PL[]']=d.data.pl 
                $scope.betData.plwin=d.data.plwin
                $scope.betData['Win_PL[]']=d.data.plwin
                $scope.betData['Bet_PK[]']=betpk
                $scope.betData['Odd_PK[]']=$scope.matchoddtype($scope.data.oddtype)
                $scope.betData.minmoney=d.data.pk.xe[2]
                $scope.betData.maxmoney=d.data.pk.xe[1]  
            }
            
         

        }
        if(d.status==116){  //维护提示
            $ionicLoading.show({ template: d.msg }); 
        }
        
         
         if(d.status==2){
            $scope.closeshowbet();
           }
        $timeout(function() {  $ionicLoading.hide(); }, 500);
        }
         
         
        //$location.url("sscchongqing")
          // $state.go("sscchongqing"); 
      }).error(function(data, status, header, config) {
          console.log(data)
        $ionicLoading.show({
          template: '获取数据失败'
        });
        $timeout(function() {
          $ionicLoading.hide();
        }, 2500);
        
      });
  

  };


  $scope.oddtype = [{
    text: "香港盘",
    value: "H"
  }, {
    text: "马来盘",
    value: "M"
  }, {
    text: "印尼盘",
    value: "I"
  }, {
    text: "欧美盘",
    value: "E"
  }];
  $scope.matchdate = [{
    text: "今日",
    value: "t"
  }, {
    text: "早餐",
    value: "m"
  }];
  $scope.data = {
    matchdate: '今日',
    oddtype: '香港盘' 
  };
  $scope.sbetdata = {
    status: 0,
    dsorcg:1
  };
  
$scope.matchoddtype=function(d){
  
  if(d=='香港盘') matchoddtype='H'
  else if(d=='马来盘') matchoddtype='M'
  else if(d=='印尼盘') matchoddtype='I'
  else if(d=='欧美盘') matchoddtype='E'
  else  matchoddtype='H'
  return matchoddtype
}
$scope.matchdatetype=function(d){
  matchdatetype='';
  if(d=='今日') matchdatetype='today'
  else if(d=='早餐') matchdatetype='Morning'
  else if(d=='滚球') {
    matchdatetype='Playing'
    if($scope.sportname=='足球'){
      $scope.sporttype='FTP';
    }
    if($scope.sportname=='篮球'){
      $scope.sporttype='BKP';
    }
  }

  return matchdatetype
}
$scope.sum=function(a,b){
  //alert(1)
  console.log(a)
  console.log(b)
  return a;
  };
$scope.parseIntMoney = function (t) {
            t.target.value = parseInt(t.target.value),
                "NaN" == t.target.value ? (t.target.value = "",
                    i.current.money = "") : t.target.value.length > 7 && (t.target.value = "",
                    i.current.money = "")
        }
$scope.makebetshow=function($http,$ionicLoading,$scope,sporttype,match_type,matchid,betmoney){
      if(!betmoney)$scope.betData.bet_money='';
      if(!$scope.user.uid){
        $ionicLoading.show({ template: '请登录' });
        $timeout(function() { $ionicLoading.hide(); }, 500);
        return;
      }
      
      console.log($scope.user)
      $scope.makebet.sporttype=sporttype
      $scope.makebet.match_type=match_type
      $ionicLoading.show({
        template: 'Loading...'
      });

      $http({
        url: '/index.php/sports/bet/makebetshow',
        dataType: 'JSON',
        method: 'post',
        data: 'dsorcg=1&oddpk='+($scope.matchoddtype($scope.data.oddtype))+'&uid='+$scope.user['uid']+'&token='+$scope.user.token+'&matchid='+matchid+'&sport_type='+sporttype+'&pk='+match_type,
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded; charset=UTF-8'
        }
      }).success(function(d) {
        if(d.login==2){
           $ionicLoading.show({ template: '请登录' });
           $timeout(function() { $ionicLoading.hide(); }, 1500);
        }else{
           $ionicLoading.show({
           template: d.msg
           });
        
        if(d.status!=0){
          betpk=Match_ShowType='';
        if(d.data.pk[2]=='QCRQ' || d.data.pk[2]=='SBRQ')betpk=d.data.pk.match_rgg;
        else if(d.data.pk[2]=='QCDX' || d.data.pk[2]=='SBDX')betpk=d.data.pk.match_dxgg;
        if (d.data.pk[2] == 'QCRQ') {
            if (d.data.data[0]['Match_ShowType'] == 'H') {
                PKINFO = '主让';
                Match_ShowType = 'H';
            } else {
                PKINFO = '客让';
                Match_ShowType = 'C';
            }
            
        }else  if (d.data.pk[2] == 'SBRQ') {
            if (d.data.data[0]['Match_Hr_ShowType'] == 'H') {
                PKINFO = '主让';
                Match_ShowType = 'H';
            } else {
                PKINFO = '客让';
                Match_ShowType = 'C';
            } 
        }

        $scope.modal.show();
        $scope.betData.uid=$scope.user.uid;
        $scope.betData.token=$scope.user.token;
        $scope.betData.Match_Name=d.data.data[0]['Match_Name']
        $scope.betData.Match_Master=d.data.data[0]['Match_Master']
        $scope.betData.Match_Guest=d.data.data[0]['Match_Guest']
        $scope.betData.BetInfo=d.data.betinfo
        $scope.betData['Sport_Type[]']=$scope.makebet.sporttype
        $scope.betData['Match_ShowType[]']=Match_ShowType
        $scope.betData['Match_Type[]']=$scope.makebet.match_type
        $scope.betData['Match_ID[]']=d.data.data[0]['Match_ID']
        $scope.betData['Bet_PL[]']=d.data.pl 
        $scope.betData.plwin=d.data.plwin
        $scope.betData['Win_PL[]']=d.data.plwin
        $scope.betData['Bet_PK[]']=betpk
        $scope.betData['Odd_PK[]']=$scope.matchoddtype($scope.data.oddtype)
        $scope.betData.minmoney=d.data.pk.xe[2]
        $scope.betData.maxmoney=d.data.pk.xe[1]  
        $ionicLoading.hide();
        }
        

        $timeout(function() { $ionicLoading.hide(); }, 1500);
        }
        
      }).error(function(error) {
        $ionicLoading.show({
          template: '获取数据失败'+error
        });
        $timeout(function() {
          $ionicLoading.hide();
        }, 2500);
        
      });
      $scope.$broadcast('scroll.refreshComplete');
}
$scope.getmatchnamelist=function($http,$ionicLoading,$scope,matchname){
        
      // alert($scope.data.matchdate+$scope.sporttype+$scope.sportname)
      if($scope.data.matchdate=='滚球'){
        if($scope.sportname=='足球') $scope.sporttype='FTP'
        else if($scope.sportname=='篮球') $scope.sporttype='BKP'

      }else {
        if($scope.sportname=='足球') $scope.sporttype='FT'
           else if($scope.sportname=='篮球') $scope.sporttype='BK'
      }
     //alert($scope.data.matchdate+$scope.sporttype+$scope.sportname)

      $ionicLoading.show({
        template: 'Loading...'
      });
      $scope.matchdata =[];
      $http({
        url: '/index.php/sports/Match/'+matchname,
        dataType: 'JSON',
        method: 'post',
        data: 'oddpk=H&',
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded; charset=UTF-8'
        }
      }).success(function(d) {

        if(d.legname==null) d.legname=[];
        if(d.legname.length) $scope.matchdata = d.legname;
        else {
           $ionicLoading.show({
              template: '暂无赛事'
            });
        }
        
         
        if(d.Success==0){
           $ionicLoading.show({ template: d.msg });
           
        }else {
          $timeout(function() { $ionicLoading.hide(); }, 200);
        }
        
      }).error(function(error) {
        $ionicLoading.show({
          template: '获取数据失败'
        });
        $timeout(function() {
          $ionicLoading.hide();
        }, 500);
        
      });
      $scope.$broadcast('scroll.refreshComplete');

}
})

.controller('main', function($scope, $ionicLoading, $timeout) {
    $scope.show = function() {
      $ionicLoading.show({
        template: 'Loading...'
      });
      $timeout(function() {
        $ionicLoading.hide();
      }, 500)

    };
    $scope.hide = function() {
      $ionicLoading.hide();
    };
     
  }) 
.controller('Football', function($scope, $ionicLoading, $timeout, $http) {   
  $scope.matchdate = [{
    text: "今日",
    value: "t"
  }, {
    text: "滚球",
    value: "r"
  }, {
    text: "早餐",
    value: "m"
  }];
  $scope.data = {
    matchdate: '今日',
    oddtype: '香港盘' 
  }; 
    $scope.sporttype='FT';
    $scope.sportname='足球';
    if($scope.data.matchdate=='滚球'){
      $scope.sporttype='FTP';
    }
     
    $scope.sportset = function() {
    $scope.theurl='Football'+$scope.matchdatetype($scope.data.matchdate);
    $scope.getmatchnamelist($http,$ionicLoading,$scope,$scope.theurl);
    }
    $scope.sportset();
   })

.controller('Basketball', function($scope, $ionicLoading, $timeout, $http) { 
  $scope.matchdate = [{
    text: "今日",
    value: "t"
  }, {
    text: "滚球",
    value: "r"
  }, {
    text: "早餐",
    value: "m"
  }];
  $scope.data = {
    matchdate: '今日',
    oddtype: '香港盘' 
  };   
    $scope.sporttype='BK';
    $scope.sportname='篮球';
    if($scope.data.matchdate=='滚球'){
      $scope.sporttype='BKP';
    }
     $scope.sportset = function() {
    $scope.theurl='Basketball'+$scope.matchdatetype($scope.data.matchdate);
    $scope.getmatchnamelist($http,$ionicLoading,$scope,$scope.theurl);
    }
    $scope.sportset();
  })
.controller('Volleyball', function($scope, $ionicLoading, $timeout, $http) {   
    $scope.sporttype='VB';
    $scope.sportname='排球';
     $scope.sportset = function() {
    $scope.theurl='Volleyball'+$scope.matchdatetype($scope.data.matchdate);
    $scope.getmatchnamelist($http,$ionicLoading,$scope,$scope.theurl);
    }
    $scope.sportset();
  })
.controller('Baseball', function($scope, $ionicLoading, $timeout, $http) {

    $scope.sporttype='BB';
    $scope.sportname='棒球';
    $scope.sportset = function() {
    $scope.theurl='Baseball'+$scope.matchdatetype($scope.data.matchdate);
    $scope.getmatchnamelist($http,$ionicLoading,$scope,$scope.theurl);
    }
    $scope.sportset();
  })
.controller('Tennis', function($scope, $ionicLoading, $timeout, $http) {   
    $scope.sporttype='TN';
    $scope.sportname='网球';
    $scope.sportset = function() {
    $scope.theurl='Tennis'+$scope.matchdatetype($scope.data.matchdate);
    $scope.getmatchnamelist($http,$ionicLoading,$scope,$scope.theurl);
    }
    $scope.sportset();
  })

.controller('matchnamelistctrl', function($scope,$http, $stateParams, $timeout,$ionicLoading,$interval) {
    $ionicLoading.show({
        template: 'Loading...'
      });
    $scope.matchname=$stateParams.match
    $scope.sporttype=$stateParams.sporttype
    $scope.data.oddtype=$stateParams.oddtype
    $scope.sportset = function(o) {
      //console.log($stateParams)

      $http({
        url: '/index.php/sports/Match/'+$stateParams.theurl,
        dataType: 'JSON',
        method: 'post',
        data: 'oddpk='+$scope.matchoddtype($stateParams.oddtype)+'&leg='+$scope.matchname,
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded; charset=UTF-8'
        }
      }).success(function(d) { 
        $scope.db = o
         
        $ionicLoading.hide();
        $scope.matchdata = d.db; 
        $scope.$broadcast('scroll.refreshComplete');
        
      }).error(function(error) {
        $ionicLoading.show({
          template: '获取数据失败'+error
        });
        $timeout(function() {
          $ionicLoading.hide();
        }, 500);
        $scope.$broadcast('scroll.refreshComplete');
      });
    }
    $scope.sportset();
   
}) 
.controller('ShowBetList', function($scope, $ionicLoading, $timeout, $http) {  
    $scope.SBD=[];
    $scope.SBD.betpage=1;
     
    $scope.SBD.dsorcg=1; 
    

    $scope.SBD.status = [{
        id: 0,
        name: '未结算'
    },{
        id: -1,
        name: '结算'
    }];
    $scope.SBD.dsorcg = [{
        id: 1,
        name: '单式'
    },{
        id: 2,
        name: '过关'
    }];
    

    $scope.getbet = function(p) {
      
     if(!p)p=1;
     $scope.SBD.betpage=p
     $http({
        url: '/index.php/sports/main/bet',
        dataType: 'JSON',
        method: 'post',
        data: 'betpage='+$scope.SBD.betpage+'&status='+$scope.sbetdata.status+'&dsorcg='+$scope.sbetdata.dsorcg+'&uid='+$scope.user.uid+'&token='+$scope.user.token,
        headers: {
          "Content-Type": 'application/x-www-form-urlencoded; charset=UTF-8'
        }
      }).success(function(d) {
        $scope.betdata=d;
        $scope.betdata.thispage=$scope.SBD.betpage;
        $scope.betdata.pagenum=[]
          for (var i = 1; d.page >= i; i++) {
            $scope.betdata.pagenum.push(i);
          };
        console.log($scope.betdata.pagenum)
        $ionicLoading.hide();
        
      }).error(function(error) {
        $ionicLoading.show({
          template: '获取数据失败'+error
        });
        $timeout(function() {
          $ionicLoading.hide();
        }, 2500);
        
      });
      $scope.$broadcast('scroll.refreshComplete');
    }

    $scope.getbet();
  })