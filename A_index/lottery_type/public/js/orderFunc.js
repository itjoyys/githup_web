
window.onload=function(){

   
  var interval = 1000; 
  function ShowCountDown(leftsecond,divname){ 

    var day1=Math.floor(leftsecond/(60*60*24)); 
    var hour=Math.floor((leftsecond-day1*24*60*60)/3600); 
    var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60); 
    var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60); 
    var cc = document.getElementById(divname);
    cc.innerHTML = hour+"小时"+minute+"分"+second+"秒"; 
      if(hour==0 && minute==0 && second==0){
        window.location.reload(); 
      }
      if (f_o_state ==1) {
         f_stime = f_stime-1;
      }else{
         o_stime = o_stime-1;
      }
       
  }

  if (f_o_state == 1) {
           //距离封盘时间
    window.setInterval(function(){ShowCountDown(f_stime,'time_over');}, interval);
  }else{ 
         //距离开盘时间
    window.setInterval(function(){ShowCountDown(o_stime,'close_time');}, interval); 
  }
  


}








