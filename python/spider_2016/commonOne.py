'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:commonOne.py
# -*- coding: utf-8 -*-
#encoding=utf-8
#北京快乐8
import copy
import MySQLdb
import string
import globalConfig
import time
from decimal import *
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_USER_P = globalConfig.DB_USER_P
DB_PASSWD_P = globalConfig.DB_PASSWD_P
DB_NAME = globalConfig.DB_NAME
DB_NAME_PRI = globalConfig.DB_NAME_P   


def win_cbet(row):
    #获取反水比例
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    #结算
    try:
        mysql="update c_bet set js=1 , status=1 , update_time=now() where id='"+str(row['id'])+"'"
        cur.execute(mysql)
        mysql="update k_user set money=money+"+str(row['win'])+"+"+str(row['fs'])+" where uid="+str(row['uid'])
        cur.execute(mysql) 
        cur.execute("select * from k_user where uid="+str(row['uid']))
        users = cur.fetchall()                    
        user =users[0]                   
        sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id, ptype ) values ('"+user['site_id']+"','"+str(row['id'])+"','"+str(user['username'])+"','"+str(row['uid'])+"','"+str(row['agent_id'])+"',14,1,'"+str(row['win'])+"',"+str(user['money'])+unicode(",now(),'自动结算单号："+str(row['did'])+" 類型：",'utf-8')+row['type']+"',0,'"+str(user['index_id'])+"','"+str(row['ptype'])+"') "
        cur.execute(sql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "会员修改失败!!!"+str(row['id'])
    con.commit()
    cur.close()     
    con.close()   


#单式输
def lost_cbet(row):
    #获取反水比例
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    #结算
    try:
        mysql="update c_bet set win=0,js=1, status=2 , update_time=now() where id="+str(row['id'])
        cur.execute(mysql)
        mysql="update k_user set money=money+"+str(row['fs'])+" where uid="+str(row['uid'])
        cur.execute(mysql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "会员修改失败!!!"+str(row['id'])
    con.commit()
    cur.close()     
    con.close() 
    
#单式和
def invalid_cbet(row):
    #获取反水比例
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    #结算
    try:
        mysql="update c_bet set win=0,js=1, status=3 , update_time=now() where id="+str(row['id'])
        cur.execute(mysql)
        mysql="update k_user set money=money+"+str(row['money'])+" where uid="+str(row['uid'])
        cur.execute(mysql)
        cur.execute("select * from k_user where uid="+str(row['uid']))
        users = cur.fetchall()                    
        user =users[0]                   
        sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id , ptype ) values ('"+user['site_id']+"','"+str(row['id'])+"','"+str(user['username'])+"','"+str(row['uid'])+"','"+str(row['agent_id'])+"',20,1,'"+str(row['money'])+"',"+str(user['money'])+unicode(",now(),'自动结算单号："+str(row['did'])+" 類型：",'utf-8')+row['type']+"',0,'"+str(user['index_id'])+"','"+str(row['ptype'])+"') "
        cur.execute(sql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])                      
        print "会员修改失败!!!"+str(row['id'])
    con.commit()
    cur.close()     
    con.close() 

#快三
def kuai3(hm,cbetball,type):
    hmarr =copy.deepcopy(hm) 
    status = 0
    if(type ==1):
        sum = int(hmarr[0])+int(hmarr[1])+int(hmarr[2])
        if (cbetball in ['大','小','单','双'] ):
            if(sum>=11 and sum<=18):
                res = '大'
            elif(sum>=3 and sum<=10):
                res = '小'
            if(sum in [3,5,7,9,11,13,15,17]):
                resd = '单' 
            elif(sum in [4,6,8,10,12,14,16,18]):
                resd = '双' 
            if((cbetball== res or cbetball== resd) and not( int(hmarr[0])==int(hmarr[1]) and int(hmarr[1])==int(hmarr[2]))):
#            if(cbetball== res or cbetball== resd):
                status=1
            else:
                status=2
        else:  
            if((sum in [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18]) and int(cbetball)==sum):
                status=1
            else:
                status=2
    elif(type ==3):
        if(int(cbetball) in [int(hmarr[0]),int(hmarr[1]),int(hmarr[2])]):
            status=1
        else:
            status=2
    elif(type ==4):
        strs = str(hmarr[0])+','+str(hmarr[1])+','+str(hmarr[2])
        print strs
        if not (cbetball =='任意豹子'):
            if((strs in ['1,1,1','2,2,2','3,3,3','4,4,4','5,5,5','6,6,6']) and strs==cbetball):
                status=1
            else:
                status=2 
        else:
            if(strs in ['1,1,1','2,2,2','3,3,3','4,4,4','5,5,5','6,6,6']):
                status=1
            else:
                status=2              
    elif(type ==5):
        cbetball =cbetball.split(',')
        i=0
        for cb in cbetball :
            if(int(cb) in hmarr):
                i+=1
        if(i == len(cbetball)):
            status=1
        else:
            status=2   
    elif(type ==6):
        cbetball =cbetball.split(',')
        i=0
        if((int(hmarr[0])==int(hmarr[1]) or int(hmarr[1])==int(hmarr[2]) or  int(hmarr[0])==int(hmarr[2])) ):
            if(int(cbetball[0]) in hmarr):
                for key,value in enumerate(hmarr):
                    if(value==int(cbetball[0])):
                        del hmarr[key]
                        break
                if(int(cbetball[1]) in hmarr):
                    status=1
                else:
                    status=2
            else:
                status=2
        else:
            status=2                                          
    return status

    
#香港六合彩
def lhc(hm , cbetball,cbetballtype, type,money=0,id=0):
    #mouse=[8,20,32,44] #鼠
    #cow=[7,19,31,43] #牛
    #tiger=[6,18,30,42] #虎
    #rabbit=[5,17,29,41] #兔
    #dragon=[4,16,28,40] #龙
    #snake=[3,15,27,39] #蛇
    #horse=[2,14,26,38] #马
    #sheep=[1,13,25,37,49] #羊
    #monkey=[12,24,36,48] #猴
    #chicken=[11,23,35,47] #鸡
    #dog=[10,22,34,46] #狗
    #pig=[9,21,33,45] #猪
    #大年三十晚上替换
    mouse=[9,21,33,45] #鼠
    cow=[8,20,32,44]  #牛
    tiger=[ 7,19,31,43]#虎
    rabbit=[ 6,18,30,42]#兔
    dragon=[5,17,29,41]#龙  
    snake=[4,16,28,40]#蛇  
    horse=[ 3,15,27,39]#马  
    sheep=[2,14,26,38]#羊   
    monkey=[1,13,25,37,49]#猴
    chicken=[12,24,36,48]#鸡 
    dog=[11,23,35,47]#狗   
    pig=[ 10,22,34,46] #猪  
    
    redball=[1,2,7,8,12,13,18,19,23,24,29,30,34,35,40,45,46]#红波
    blueball=[3,4,9,10,14,15,20,25,26,31,36,37,41,42,47,48]#蓝波
    greenball=[5,6,11,16,17,21,22,27,28,32,33,38,39,43,44,49]#绿波
    cosingle=[1,3,5,7,9,10,12,14,16,18,21,23,25,27,29,30,32,34,36,38,41,43,45,47]#合单
    cotwin=[2,4,6,8,11,13,15,17,19,20,22,24,26,28,31,33,35,37,39,40,42,44,46,48]#合双
    big=[25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48]#大
    little=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24]#小
    single=[1,3,5,7,9,11,13,15,17,19,21,23,25,27,29,31,33,35,37,39,41,43,45,47]#单
    double=[2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48]#双
    cobig=[7,8,9,16,17,18,19,25,26,27,28,29,34,35,36,37,38,39,43,44,45,46,47,48]#合大
    colittle=[1,2,3,4,5,6,10,11,12,13,14,15,20,21,22,23,24,30,31,32,33,40,41,42]#合小
#    littledouble=[2,4,6,8,10,12,14,16,18,20,22,24]#小双
#    bigdouble=[26,28,30,32,34,36,38,40,42,44,46,48]#大双
    redsinger=[1,7,13,19,23,29,35,45]#红单
    reddouble=[2,8,12,18,24,30,34,40,46]#红双
    redbig=[29,30,34,35,40,45,46]#红大
    redlittle=[1,2,7,8,12,13,18,19,23,24]#红小
    bluesingle=[3,9,15,25,31,37,41,47]#蓝单
    bluedouble=[4,10,14,20,26,36,42,48]#蓝双
    bluebig=[25,26,31,36,37,41,42,47,48]#蓝大
    bluelittle=[3,4,9,10,14,15,20]#蓝小
    greensongle=[5,11,17,21,27,33,39,43,49]#绿单
    greendouble=[6,16,22,28,32,38,44]#绿双
    greenbig=[27,28,32,33,38,39,43,44,49]#绿大
    greenlittle=[5,6,11,16,17,21,22]#绿小
    redcosingle=[1,7,23,29,45,12,18,30,34]#红合单
    redcodouble=[13,19,35,2,8,24,40,46]#红合双
    greencosingle=[5,16,21,27,32,38,43]#绿合单
    greencodouble=[6,11,17,22,28,33,39,44]#绿合双
    bluecosingle=[3,9,25,41,47,10,14,36]#蓝合单
    bluecodouble=[15,31,37,4,20,26,42,48]#蓝合双
    zerotitle=[1,2,3,4,5,6,7,8,9]#0头
    onetitle=[10,11,12,13,14,15,16,17,18,19]#1头
    twotitle=[20,21,22,23,24,25,26,27,28,29]#2头
    threetitle=[30,31,32,33,34,35,36,37,38,39]#3头
    fourtitle=[40,41,42,43,44,45,46,47,48,49]#4头
    zerotail=[10,20,30,40]#0尾
    onetail=[1,11,21,31,41]#1尾
    twotail=[2,12,22,32,42]#2尾
    threetail=[3,13,23,33,43]#3尾
    fourtail=[4,14,24,34,44]#4尾
    fivetail=[5,15,25,35,45]#5尾
    sixtail=[6,16,26,36,46]#6尾
    seventail=[7,17,27,37,47]#7尾
    eighttail=[8,18,28,38,48]#8尾
    ninetail=[9,19,29,39,49]#9尾
    tailtitle=[0,1,2,3,4,10,20,30,40,11,21,31,41,12,22,32,42,13,23,33,43,14,24,34,44]#尾小
    tailbig=[5,6,7,8,9,15,25,35,45,16,26,36,46,17,27,37,47,18,28,38,48,19,29,39]#尾大
    bigsingle=[25,27,29,31,33,35,37,39,41,43,45,47]#大单
    bigdouble=[26,28,30,32,34,36,38,40,42,44,46,48]#大双
    littlesingle=[1,3,5,7,9,11,13,15,17,19,21,23]#小单
    littledouble=[2,4,6,8,10,12,14,16,18,20,22,24]#小双
    poultry=cow+horse+sheep+chicken+dog+pig#家禽
    wildbeast=mouse+tiger+rabbit+dragon+snake+monkey#野兽
    redsinglebig=[29,35,45]#红单大
    redsinglelittle=[1,7,13,19,23]#红单小
    reddoublebig=[30,34,40,46]#红双大
    reddoublelittle=[2,8,12,18,24]#红双小
    bluesinglebig=[25,31,37,41,47]#蓝单大
    bluesinglelittle=[3,9,15]#蓝单小
    bluedoublebig=[26,36,42,48]#蓝双大
    bluedoublelittle=[4,10,14,20]#蓝双小
    greensinglebig=[27,33,39,43,49]#绿单大
    greensinglelittle=[5,11,17,21]#绿单小
    greendoublebig=[28,32,38,44]#绿双大
    greendoublelittle=[6,16,22]#绿双小
    onetonine=[1,2,3,4,5,6,7,8,9,10]#一到十
    onetentotwoten=[11,12,13,14,15,16,17,18,19,20]#11到20
    twotentothreeten=[21,22,23,24,25,26,27,28,29,30]#21到30
    threetentofiveten=[31,32,33,34,35,36,37,38,39,40]#31到40
    fivetentofivenine=[41,42,43,44,45,46,47,48,49]#41到49
    lhcmap={'鼠':mouse,'牛':cow,'虎':tiger,'兔':rabbit,'龙':dragon,'蛇':snake,'马':horse,'羊':sheep
            ,'猴':monkey,'鸡':chicken,'狗':dog,'猪':pig,'红波':redball,'蓝波':blueball,'绿波':greenball,'合单':cosingle
            ,'合双':cotwin,'大':big,'小':little,'单':single,'双':double,'合大':cobig,'合小':colittle,'小双':littledouble,'大双':bigdouble
            ,'红单':redsinger,'红双':reddouble,'红大':redbig,'红小':redlittle,'蓝单':bluesingle,'蓝双':bluedouble,'蓝大':bluebig
            ,'蓝小':bluelittle,'绿单':greensongle,'绿双':greendouble,'绿大':greenbig,'绿小':greenlittle,'红合单':redcosingle,'红合双':redcodouble
            ,'绿合单':greencosingle,'绿合双':greencodouble,'蓝合单':bluecosingle,'蓝合双':bluecodouble,'蓝合双':bluecodouble,'0头':zerotitle,'1头':onetitle
            ,'2头':twotitle,'3头':threetitle,'4头':fourtitle,'0尾':zerotail,'1尾':onetail,'2尾':twotail,'3尾':threetail,'4尾':fourtail,'5尾':fivetail
            ,'6尾':sixtail,'7尾':seventail,'8尾':eighttail,'9尾':ninetail,'尾小':tailtitle,'尾大':tailbig,'大单':bigsingle,'大双':bigdouble,'小单':littlesingle
            ,'小双':littledouble,'家禽':poultry,'野兽':wildbeast,'红单大':redsinglebig,'红单小':redsinglelittle,'红双大':reddoublebig,'红双小':reddoublelittle,'蓝单大':bluesinglebig
            ,'蓝单小':bluesinglelittle,'蓝双大':bluedoublebig,'蓝双小':bluedoublelittle,'绿单大':greensinglebig,'绿单小':greensinglelittle,'绿双大':greendoublebig,'绿双小':greendoublelittle
            ,'1-10':onetonine,'11-20':onetentotwoten,'21-30':twotentothreeten,'31-40':threetentofiveten,'41-49':fivetentofivenine}
    status =2
    num = int(hm[6])  #特码
    zmz = [int(hm[0]),int(hm[1]),int(hm[2]),int(hm[3]),int(hm[4]),int(hm[5])] #正码
    zmco = [hm[7],hm[8],hm[9],hm[10],hm[11],hm[12],hm[13]] #全码生肖
    print cbetball
    if (type == 1):
        if(cbetball.isdigit() ):
            if(num==int(cbetball)):
                status = 1
            else:
                status = 2
        elif(num in lhcmap[str(cbetball)]):
            status =1
        elif(num==49):
            status =3
        else:
            status =2
    elif(type == 2):
        sum = int(hm[0])+int(hm[1])+int(hm[2])+int(hm[3])+int(hm[4])+int(hm[5])+int(hm[6])
        if(cbetball.isdigit() and int(cbetball) in zmz):
            status = 1
        elif(cbetball =='总大'):
            if(sum>=175):
                status = 1
        elif(cbetball =='总小'):
            if(sum<=174):
                status = 1
        elif(cbetball =='总双'):
            if(sum%2==0):
                status = 1
        elif(cbetball =='总单'):
            if(sum%2==1):
                status = 1
        elif(cbetball =='总尾大'):
            sum=str(sum)
            sumw = sum[-1:]
            if(int(sumw)>=5):
                status = 1
            else:
                status = 2
        elif(cbetball =='总尾小'):
            sum=str(sum)
            sumw = sum[-1:]            
            if(int(sumw)<5):
                status = 1
            else:
                status = 2
        elif(cbetball =='龙'):
            if(int(hm[0])>int(hm[6])):
                status = 1
            else:
                status = 2                
        elif(cbetball =='虎'):    
            if(int(hm[6])>int(hm[0])):
                status = 1
            else:
                status = 2    
        else:
            status =2
    elif(type == 3):
        if(cbetballtype=='正1特' and int(cbetball)==zmz[0] ):
            status = 1
        elif(cbetballtype =='正2特' and int(cbetball)==zmz[1] ):
            status = 1
        elif(cbetballtype =='正3特' and int(cbetball)==zmz[2] ):
            status = 1
        elif(cbetballtype =='正4特' and int(cbetball)==zmz[3]):
            status = 1
        elif(cbetballtype =='正5特' and int(cbetball)==zmz[4]):
            status = 1
        elif(cbetballtype =='正6特' and int(cbetball)==zmz[5]):
            status = 1
        else:        
            status = 2
    elif(type == 4):
        if(cbetballtype=='正码1' ):
            if(zmz[0]==49 and not cbetball in ['红波','绿波','蓝波']):
                status =3
            elif( zmz[0] in lhcmap[str(cbetball)] ):
                status = 1
            else:
                status = 2
        elif(cbetballtype =='正码2'):
            if(zmz[1]==49 and not cbetball in ['红波','绿波','蓝波']):
                status =3
            elif( zmz[1] in lhcmap[str(cbetball)] ):
                status = 1
            else:
                status = 2            
        elif(cbetballtype =='正码3' ):
            if(zmz[2]==49 and not cbetball in ['红波','绿波','蓝波']):
                status =3
            elif( zmz[2] in lhcmap[str(cbetball)] ):
                status = 1
            else:
                status = 2   
        elif(cbetballtype =='正码4' ):
            if(zmz[3]==49 and not cbetball in ['红波','绿波','蓝波']):
                status =3
            elif( zmz[3] in lhcmap[str(cbetball)] ):
                status = 1
            else:
                status = 2 
        elif(cbetballtype =='正码5' ):
            if(zmz[4]==49 and not cbetball in ['红波','绿波','蓝波']):
                status =3
            elif( zmz[4] in lhcmap[str(cbetball)] ):
                status = 1
            else:
                status = 2 
        elif(cbetballtype =='正码6' ):
            if(zmz[5]==49 and not cbetball in ['红波','绿波','蓝波']):
                status =3
            elif( zmz[5] in lhcmap[str(cbetball)] ):
                status = 1
            else:
                status = 2 
    elif(type == 5):
        cbetballtype=cbetballtype.strip(',').split(',')
        cbetball    =cbetball.strip(',').split(',')
        i = 0
        for key,tp in enumerate(cbetballtype):
            if(tp=='正码1' ):
                if(zmz[0]==49 ):
                    status =3
                    break
                elif( zmz[0] in lhcmap[str(cbetball[key])] ):
                    i+=1
            elif(tp =='正码2'):
                if(zmz[1]==49 ):
                    status =3
                    break
                elif( zmz[1] in lhcmap[str(cbetball[key])] ):
                    i+=1         
            elif(tp =='正码3' ):
                if(zmz[2]==49 ):
                    status =3
                    break
                elif( zmz[2] in lhcmap[str(cbetball[key])] ):
                    i+=1     
            elif(tp =='正码4' ):
                if(zmz[3]==49 ):
                    status =3
                    break
                elif( zmz[3] in lhcmap[str(cbetball[key])] ):
                    i+=1    
            elif(tp =='正码5' ):
                if(zmz[4]==49 ):
                    status =3
                    break
                elif( zmz[4] in lhcmap[str(cbetball[key])] ):
                    i+=1 
            elif(tp =='正码6' ):
                if(zmz[5]==49 ):
                    status =3
                    break
                elif( zmz[5] in lhcmap[str(cbetball[key])] ):
                    i+=1  
        if(not status==3):
            if(i == len(cbetballtype)):
                status =1
            else:
                status =2
    elif(type == 6):
        cbetball    =cbetball.split(',')
        if(cbetballtype=='三全中' ):
            if(int(cbetball[0]) in zmz and int(cbetball[1]) in zmz  and int(cbetball[2]) in zmz):
                status = 1
            else:
                status = 2
        elif(cbetballtype =='中二:20;中三:85' ):
            ci = 0
            for cbet in cbetball:
                if(int(cbet) in zmz):
                    ci+=1
            if(ci>=2):
                if(ci==2):
                    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
                    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
                    temp=cbetballtype.split(';')
                    date = temp[0].split(":")
                    odd = Decimal(date[1])
                    win = money*odd
                    mysql="update c_bet set win="+str(win)+",odds="+str(odd)+" where id="+str(id)
                    print mysql
                    cur.execute(mysql)
                    con.commit()
                    cur.close()     
                    con.close()
                status = 1
            else:
                status = 2
        elif(cbetballtype =='二全中' ):
            if(int(cbetball[0]) in zmz and int(cbetball[1]) in zmz  ):
                status = 1
            else:
                status = 2
        elif(cbetballtype =='中特:30;中二:50' ):
            if(int(cbetball[0]) in zmz and int(cbetball[1]) in zmz  ):               
                status = 1
            elif((int(cbetball[0]) in zmz and int(cbetball[1]) == num) or ((int(cbetball[1]) in zmz and int(cbetball[0]) == num))):
                con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
                cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
                temp=cbetballtype.split(';')
                date = temp[0].split(":")
                odd = Decimal(date[1])
                win = money*odd
                mysql="update c_bet set win="+str(win)+",odds="+str(odd)+" where id="+str(id)
                cur.execute(mysql)
                con.commit()
                cur.close()     
                con.close() 
                status = 1
            else:
                status = 2
        elif(cbetballtype =='特串'):
            if((int(cbetball[0]) in zmz and int(cbetball[1]) == num) or ((int(cbetball[1]) in zmz and int(cbetball[0]) == num))):
                status = 1
            else:
                status = 2
        elif(cbetballtype =='四中一' ):
            ci = 0
            for cbet in cbetball:
                if(int(cbet) in zmz):
                    ci+=1
            if(ci==1):
                status = 1
            else:
                status = 2            
        else:        
            status = 2
    elif(type == 7):
        if(num ==49 ):
            status = 3
        elif(num in lhcmap[str(cbetball)]):
            status = 1
        else:        
            status = 2
#     elif(type == 8):
#         ci = 0
#         for z in zmz+[num]:
#             if(z in lhcmap[cbetball]):
#                 ci+=1
#         if(ci >=1 ):
#             status = 1
#         else:        
#             status = 2
    elif(type == 9):
        ci = 0
        for z in zmz+[num]:
            if(str(z)[-1:] == cbetball):
                ci+=1
        if(ci >=1 ):
            status = 1
        else:        
            status = 2      
    elif(type == 10):
        cozodiac = ['二肖','三肖','四肖','五肖','六肖','七肖','八肖','九肖','十肖','十一肖']
        if(cbetballtype =='特肖'):
            print num
            if(num in lhcmap[str(cbetball)]):
                status = 1
            else:        
                status = 2     
        elif(cbetballtype =='一肖'):
            ci = 0
            for z in zmz+[num]:
                if(z in lhcmap[str(cbetball)]):
                    ci+=1
            if(ci >=1 ):
                status = 1
            else:        
                status = 2  
        elif(cbetballtype in cozodiac):
            cbetball =cbetball.strip(',').split(',')
            sumall = []
            for cb in cbetball:
                sumall+=lhcmap[str(cb)]
            if(num == 49):
                status = 3
            elif(num in sumall):
                status = 1
            else:        
                status = 2 
        else:
            status = 2 
    elif(type == 11):
        cozodiac = ['二肖连中','三肖连中','四肖连中','五肖连中']
        uncozodiac = ['二肖连不中','三肖连不中','四肖连不中']
        if(cbetballtype in cozodiac):
            cbetball =cbetball.strip(',').split(',')
            ci =0
            for cb in cbetball:
                if(cb in zmco):
                    ci+=1
            if(ci == len(cbetball)):
                status = 1
            else:        
                status = 2     
        elif(cbetballtype in uncozodiac):
            cbetball =cbetball.strip(',').split(',')
            for cb in cbetball:
                if(cb in zmco):
                    status = 2
                    break
                else:
                    status = 1
        else:
            status = 2
    elif(type == 12):
        cozodiac = ['二尾连中','三尾连中','四尾连中']
        uncozodiac = ['二尾连不中','三尾连不中','四尾连不中']
        ctail =[]
        for z in zmz+[num]:
            ctail+=[str(z)[-1:]]
        ctail=set(ctail)
        if(cbetballtype in cozodiac):
            cbetball =cbetball.strip(',').split(',')
            ci =0
            for cb in cbetball:
                if(cb in ctail):
                    ci+=1
            if(ci == len(cbetball)):
                status = 1
            else:        
                status = 2     
        elif(cbetballtype in uncozodiac):
            cbetball =cbetball.strip(',').split(',')
            sumall = []
            for cb in cbetball:
                if(cb in ctail):
                    status = 2
                    break
                else:
                    status = 1 
        else:
            status = 2
    elif(type == 13):
        cozodiac = ['五不中','六不中','七不中','八不中','九不中','十不中','十一不中','十二不中']
        
        if(cbetballtype in cozodiac):
            cbetball =cbetball.strip(',').split(',')
            for cb in cbetball:
                if(int(cb) in  zmz+[num]):
                    status = 2
                    break
                else:
                    status = 1   
        else:
            status = 2 
    return status
    
#北京快乐8
def Kl8_Auto(num , type):
    zh = num[0]+num[1]+num[2]+num[3]+num[4]+num[5]+num[6]+num[7]+num[8]+num[9]+num[10]+num[11]+num[12]+num[13]+num[14]+num[15]+num[16]+num[17]+num[18]+num[19];
    if(type==0):
        return zh
    
    if(type==1):#总和大小
        if(zh>810):
            return '总和大'
        elif(zh<810):
            return '总和小'
        elif(zh==810):
            return '总和810'
    
    if(type==2):#总和双单
        if(zh%2==0):
            return '总和双'
        else:
            return '总和单'
    
    if(type==3):#上中下盘
        i=0
        j=0
        for value in num:
            if(value<=40):
                i+=1
            else:
                j+=1
        if(i>j):
            return '上盘'
        elif(i<j):
            return '下盘'
        else:
            return '中盘'
    
    if(type==4):#奇偶和盘
        i=0
        j=0
        for value in num:
            if (value%2==0):
                i+=1
            else:
                j+=1
        print i
        print j
        if(i>j):
            return '偶盘'
        elif(i<j):
            return '奇盘'
        else:
            return '和盘'
    return 'end'   

def G10_Auto(num , type):
    zh = num[0]+num[1]+num[2]+num[3]+num[4]+num[5]+num[6]+num[7]
    if(type==1):
        return zh
    if(type==2):
        if(zh>=85 and zh<=132):
            return '总和大'
        if(zh>=36 and zh<=83):
            return '总和小'
        if(zh==84):
            return '和'
    if(type==3):
        if(zh%2==0):
            return '总和双'
        else:
            return '总和单'
    if(type==4):
        zh=str(zh)
        zhws = zh[-1:]
        if(int(zhws)>=5):
            return '总和尾大'
        else:
            return '总和尾小'
    if(type==5):
        if(num[0]>num[7]):
            return '龙'
        elif(num[0]<num[7]):
            return '虎'
    return 'end'    
def BuLing ( num ):
    if ( num<10 ):
        num = '0'+'%d'%num   
    return str(num)

#广东快乐十分单双
def G10_Ds(ball):
    if(ball%2==0):
        return '双'
    else:
        return '单'

#广东快乐十分大小
def G10_Dx(ball):
    if(ball>10):
        return '大'
    else:
        return '小'
    
#广东快乐十分尾数大小
def G10_WsDx(ball):
    ball =str(ball)
    wsdx = ball[-1:]
    if(int(wsdx)>4):
        return '尾大'
    else:
        return '尾小'

#广东快乐十分合数单双
def G10_HsDs(ball):
    ball = BuLing(ball)
    ball = str(ball)
    a = ball[0:1]
    b = ball[-1:]
    c = int(a)+int(b)
    if(c%2==0):
        return '合数双'
    else:
        return '合数单'

#广东快乐十分号码方位
def G10_Fw(ball):
    if(BuLing(ball) == '01' or BuLing(ball) == '05' or BuLing(ball) == '09' or BuLing(ball) == '13' or BuLing(ball) == '17'):
        fw = '东'
    elif(BuLing(ball) == '02' or BuLing(ball) == '06' or BuLing(ball) == '10' or BuLing(ball) == '14' or BuLing(ball) == '18'):
        fw = '南'
    elif(BuLing(ball) == '03' or BuLing(ball) == '07' or BuLing(ball) == '11' or BuLing(ball) == '15' or BuLing(ball) == '19'):
        fw = '西'
    else:
        fw = '北'
    return fw
#广东快乐十分号码中发白
def G10_Zfb(ball):
    if(BuLing(ball) == '01' or BuLing(ball) == '02' or  BuLing(ball) == '03' or BuLing(ball) == '04' or BuLing(ball) == '05' or BuLing(ball) == '06' or BuLing(ball) == '07'):
        zfb = '中'
    elif(BuLing(ball) == '08' or BuLing(ball) == '09' or BuLing(ball) == '10' or BuLing(ball) == '11' or BuLing(ball) == '12' or BuLing(ball) == '13' or BuLing(ball) == '14'):
        zfb = '发'
    else:
        zfb = '白'
    return zfb

def G10_nextNum(hm,mingxi2,mingxi3):
    status=2
    mingxi3 =mingxi3.strip(',').split(',')
    if(mingxi2=='任选二' or mingxi2=='任选三' or mingxi2=='任选四' or mingxi2=='任选五' ):
        ci=0
        for cb in mingxi3:
            if(int(cb) in hm):
                ci+=1
        if(ci == len(mingxi3)):
            status = 1

    elif(mingxi2=='任选二组'):
        ci=0
        num =len(hm)
        for key,value in enumerate(hm):
            if( value in mingxi3 and (key<num-1)):
                if(hm[key+1] in mingxi3):
                    status = 1
    return status
#重庆时时彩单双
def Ssc_Ds(ball):
    if(ball%2==0):
        return '双'
    else:
        return '单'
#重庆时时彩大小
def Ssc_Dx(ball):
    if(ball>4):
        return '大'
    else:
        return '小'
#重庆时时彩开奖函数
def Ssc_Auto(num ,type,flag=0):
    zh = num[0]+num[1]+num[2]+num[3]+num[4]
    if(type==1):
        return zh
    if(type==2):
        if(zh>=23):
            return '总和大'
        if(zh<=22):
            return '总和小'
    if(type==3):
        if(zh%2==0):
            return '总和双'
        else:
            return '总和单'
    if(type==4):
        if(num[0]>num[4]):
            return '龙'
        if(num[0]<num[4]):
            return '虎'
        if(num[0]==num[4]):
            return '和'
    if(type==5):
        n1=num[0]
        n2=num[1]
        n3=num[2]
        if((n1==0 or n2==0 or n3==0) and (n1==9 or n2==9 or n3==9)):
            if(n1==0):
                n1=10   
            if(n2==0):
                n2=10   
            if(n3==0):
                n3=10  
            
        if(n1==n2 and n2==n3):
            return "豹子"
        elif((n1==n2) or (n1==n3) or (n2==n3)):
            return "对子"   
        elif((n1==10 or n2==10 or n3==10) and (n1==9 or n2==9 or n3==9) and (n1==1 or n2==1 or n3==1)):
            return "顺子"            
        elif( ( (abs(n1-n2)==1) and (abs(n2-n3)==1) ) or ((abs(n1-n2)==2) and (abs(n1-n3)==1) and (abs(n2-n3)==1)) or((abs(n1-n2)==1) and (abs(n1-n3)==1)) ):
            return "顺子"    
        elif((abs(n1-n2)==1) or (abs(n1-n3)==1) or (abs(n2-n3)==1)):
            return "半顺"   
        else:
            return "杂六"   
    if(type==6):
        n1=num[1]
        n2=num[2]
        n3=num[3]
        if((n1==0 or n2==0 or n3==0) and (n1==9 or n2==9 or n3==9)):
            if(n1==0):
                n1=10    
            if(n2==0):
                n2=10    
            if(n3==0):
                n3=10                
        if(n1==n2 and n2==n3):  
            return "豹子"
        elif((n1==n2) or (n1==n3) or (n2==n3)):
            return "对子"   
        elif((n1==10 or n2==10 or n3==10) and (n1==9 or n2==9 or n3==9) and (n1==1 or n2==1 or n3==1)):
            return "顺子"            
        elif( ( (abs(n1-n2)==1) and (abs(n2-n3)==1) ) or ((abs(n1-n2)==2) and (abs(n1-n3)==1) and (abs(n2-n3)==1)) or ((abs(n1-n2)==1) and (abs(n1-n3)==1)) ):
            return "顺子"   
        elif((abs(n1-n2)==1) or (abs(n1-n3)==1) or (abs(n2-n3)==1)):
            return "半顺"  
        else:
            return "杂六"   
    if(type==7):
        n1=num[2]
        n2=num[3]
        n3=num[4]
        if((n1==0 or n2==0 or n3==0) and (n1==9 or n2==9 or n3==9)):
            if(n1==0):
                n1=10    
            if(n2==0):
                n2=10    
            if(n3==0):
                n3=10           
        if(n1==n2 and n2==n3):   
            return "豹子"
        elif((n1==n2) or (n1==n3) or (n2==n3)):
            return "对子"    
        elif((n1==10 or n2==10 or n3==10) and (n1==9 or n2==9 or n3==9) and (n1==1 or n2==1 or n3==1)):
            return "顺子"            
        elif( ( (abs(n1-n2)==1) and (abs(n2-n3)==1) ) or ((abs(n1-n2)==2) and (abs(n1-n3)==1) and (abs(n2-n3)==1)) or ((abs(n1-n2)==1) and (abs(n1-n3)==1)) ):
            return "顺子"    
        elif((abs(n1-n2)==1) or (abs(n1-n3)==1) or (abs(n2-n3)==1)):
            return "半顺"    
        else:
            return "杂六"   

    
    if(type==8):#斗牛
        n1=num[0]
        n2=num[1]
        n3=num[2]
        n4=num[3]
        n5=num[4]
        array =copy.deepcopy(num) 
        zh=-1
        #zh1=n1+n2+n3+n4+n5;
        zh2=0
#        for(i=0;i<5;i++){
        i =0
        while (i<5):
            j=i+1
            ii = j
           # for(ii=j;ii<5;ii++){
            while (ii<5):    
                jj=ii+1
                #for(iii=jj;iii<5;iii++){
                iii=jj
                while (iii<5):                            
                    zh=array[i]+array[ii]+array[iii]            
                    if(zh==0 or zh%10==0):                        
                        for key,value in enumerate(array):
                            if not (key==i or key==ii or key==iii ):
                                zh2+=value
                        break
                    iii+=1
                if(zh==0 or zh%10==0): break
                ii+=1    
            if(zh==0 or zh%10==0): break 
            i+=1
        print zh
        print zh2
        #}//echo "--".zh."|".zh2."<br>";
        status=''
        if(zh==0 or zh%10==0):
            if(zh2>10):
                status= "牛"+str(zh2-10)
            elif((zh+zh2)==0 or zh2==10 ):
                status= "牛牛"
            else:
                status= "牛"+str(zh2)
        else:
            status= "没牛"
        if(flag == 1):
            if(status in ['牛6','牛7','牛8','牛9','牛牛']):
                status = '牛大'
            elif(status in ['牛1','牛2','牛3','牛4','牛5']):
                status = '牛小'
        if(flag == 2):
            if(status in ['牛1','牛3','牛5','牛7','牛9']):
                status = '牛单'
            elif(status in ['牛2','牛4','牛6','牛8','牛牛']):
                status = '牛双'  
        print  status     
        return status
    if(type==9):#梭哈
        if(flag ==1):
            n1=num[0]
            n2=num[1]
            n3=num[2]
            n4=num[3]
            n5=num[4]
            if(n1==n2 and n2==n3 and n3==n4 and n4==n5):
                return "五条"
        elif(flag ==2):
            i =0
            array =copy.deepcopy(num) 
            arr = {0:0,1:0,2:0,3:0,4:0,5:0,6:0,7:0,8:0,9:0}
            for key,value in enumerate(array):
                arr[value]+=1
            for key in arr:
                if(arr[key] == 4):
                    i=1
            if(i==1):
                return "四条"
        elif(flag ==3):    
            i =0
            array =copy.deepcopy(num) 
            i=j=0
            arr = {0:0,1:0,2:0,3:0,4:0,5:0,6:0,7:0,8:0,9:0}
            for key,value in enumerate(array):
                arr[value]+=1
            print arr
            for key in arr:
                if(arr[key] == 3):
                    i=1
                if(arr[key] == 2):
                    j=1
            if(i==1 and j==1):
                return "葫芦"
        elif(flag ==4):     
            n1=num[0] 
            if(n1==10):n1=0 
            n2=n1+1
            if(n2==10):n2=0
            n3=n2+1
            if(n3==10):n3=0
            n4=n3+1
            if(n4==10):n4=0
            n5=n4+1
            if(n5==10):n5=0
            if(n1==num[0] and n2==num[1] and n3==num[2] and n4==num[3] and n5==num[4] ):
                return "顺子"
        elif(flag ==5):            
            i =0
            array =copy.deepcopy(num) 
            i=j=0
            arr = {0:0,1:0,2:0,3:0,4:0,5:0,6:0,7:0,8:0,9:0}
            for key,value in enumerate(array):
                arr[value]+=1
            for key in arr:
                if(arr[key] == 3):
                    i=1
                if(arr[key] == 2):
                    j=1
            if(i==1 and j==0):
                return "三条"
        elif(flag ==6):
            i =0
            array =copy.deepcopy(num) 
            arr = {0:0,1:0,2:0,3:0,4:0,5:0,6:0,7:0,8:0,9:0}
            for key,value in enumerate(array):
                arr[value]+=1
            for key in arr:
                if(arr[key] == 2):
                    i+=1
            if(i==2):
                return "两对"
        elif(flag ==7):           
            i = 0
            j = 0
            array =copy.deepcopy(num) 
            arr = {0:0,1:0,2:0,3:0,4:0,5:0,6:0,7:0,8:0,9:0}
            for key,value in enumerate(array):
                arr[value]+=1
            for key in arr:
                if(arr[key] == 2):
                    i+=1
                if(arr[key] == 1):
                    j+=1
            if (i==1 and j==3):
                return "一对"
        elif(flag ==8): 
            i =0
            array =copy.deepcopy(num) 
            arr = {0:0,1:0,2:0,3:0,4:0,5:0,6:0,7:0,8:0,9:0}
            for key,value in enumerate(array):
                arr[value]+=1
            for key in arr:
                if(arr[key] == 1):
                    i+=1
            if (i==5):
                return "散号"
    return 'end'        
#北京PK拾开奖函数
def Pk10_Auto(num , type , ballnum):
    zh = num[0]+num[1]
    if(type==1):
        return zh
    if(type==2):
        if(zh>11):
            return '冠亚大'
        else:
            return '冠亚小'
    if(type==3):
        if(zh%2==0):
            return '冠亚双'
        else:
            return '冠亚单'
    if(type==4):
        if(num[0]>num[9]):
            return '龙'
        elif(num[0]<num[9]):
            return '虎'
    if(type==5):
        if(num[1]>num[8]):
            return '龙'
        elif(num[1]<num[8]):
            return '虎'

    if(type==6):
        if(num[2]>num[7]):
            return '龙'
        elif(num[2]<num[7]):
            return '虎'

    if(type==7):
        if(num[3]>num[6]):
            return '龙'
        elif(num[3]<num[6]):
            return '虎'
    if(type==8):
        if(num[4]>num[5]):
            return '龙'
        elif(num[4]<num[5]):
            return '虎'
    if(type==9):
        if(ballnum>5):
            return '大'
        else:
            return '小'
    if(type==10):
        if(ballnum%2==0):
            return '双'
        else:
            return '单'
    return 'end'   
#福彩3D
def FC3D_Auto(num , type):
    zh = num[0]+num[1]+num[2]
    if(type==0):
        return zh
    
    if(type==1 or type==2 or type==3):#第一~三球大小
    
        if(type==1):qnum = num[0]
        if(type==2):qnum = num[1]
        if(type==3):qnum = num[2]
        
        if(qnum>=5):
            return '大'
        else:
            return '小'
    
    if(type==4 or type==5 or type==6):
        if(type==4):qnum = num[0]
        if(type==5):qnum = num[1]
        if(type==6):qnum = num[2]
        
        if(qnum%2==0):
            return '双'
        else:
            return '单'
    
    if(type==7):#总和大小
        if(zh>=14):
            return '总和大'
        else:
            return '总和小'     
    
    if(type==8):#总和双单
        if(zh%2==0):
            return '总和双'
        else:
            return '总和单'
    
    if(type==9):
        if(num[0]>num[2]):
            return '龙'
        if(num[0]<num[2]):
            return '虎';
        if(num[0]==num[2]):
            return '和'
    
    if(type==10):
        n1=num[0]
        n2=num[1]
        n3=num[2]
        if((n1==0 or n2==0 or n3==0) and (n1==9 or n2==9 or n3==9)):
            if(n1==0):
                n1=10   
            if(n2==0):
                n2=10   
            if(n3==0):
                n3=10   
            
        if(n1==n2 and  n2==n3):   
            return "豹子"
        elif((n1==n2) or (n1==n3) or (n2==n3)):
            return "对子";    
        elif((n1==10 or n2==10 or n3==10) and (n1==9 or n2==9 or n3==9) and (n1==1 or n2==1 or n3==1)):
            return "顺子";            
        elif( ( (abs(n1-n2)==1) and (abs(n2-n3)==1) ) or ((abs(n1-n2)==2) and (abs(n1-n3)==1) and (abs(n2-n3)==1)) or ((abs(n1-n2)==1) and (abs(n1-n3)==1)) ):
            return "顺子";    
        elif((abs(n1-n2)==1) or (abs(n1-n3)==1) or (abs(n2-n3)==1)):
            return "半顺"    
        else:
            return "杂六" 
    return 'end'      

#end of model
