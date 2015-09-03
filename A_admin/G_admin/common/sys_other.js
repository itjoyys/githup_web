
 window.onload=function(){
    document.getElementById("other").onchange=function(){
      var val=this.value;
      if(val==1){
        window.location.href="/929ht/xtgl/set_site.php";
      }else if(val==2){
        window.location.href="/929ht/xtgl/dqxz.php";
      }else if(val==3){
        window.location.href="/929ht/xxgl/add.php?1=1";
      }else if(val==4){
        window.location.href="/929ht/xxgl/sys_msg.php";
      }else{
        window.location.href="/929ht/xxgl/ssgl.php";
      }
    }
  }