'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:balance8.py
# -*- coding: utf-8 -*-
#encoding=utf-8


import MySQLdb
import commonOne
import globalConfig
import time
import string
from decimal import *
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_USER_P = globalConfig.DB_USER_P
DB_PASSWD_P = globalConfig.DB_PASSWD_P
DB_NAME =  globalConfig.DB_NAME
DB_NAME_PRI = globalConfig.DB_NAME_P     
#
#

def balance8(qishu):   
    
#   获取开奖号码    
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    cur.execute("select * from c_auto_8 where qishu = '"+qishu+"' ")
    rs = cur.fetchall()
    hm  = [rs[0]['ball_1'],rs[0]['ball_2'],rs[0]['ball_3'],rs[0]['ball_4'],rs[0]['ball_5'],rs[0]['ball_6'],rs[0]['ball_7'],rs[0]['ball_8'],rs[0]['ball_9'],rs[0]['ball_10'],rs[0]['ball_11'],rs[0]['ball_12'],rs[0]['ball_13'],rs[0]['ball_14'],rs[0]['ball_15'],rs[0]['ball_16'],rs[0]['ball_17'],rs[0]['ball_18'],rs[0]['ball_19'],rs[0]['ball_20']]
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    cur.execute("select * from c_bet where type='北京快乐8' and js=0 and qishu='"+qishu+"' order by addtime asc")
    rows = cur.fetchall()
    print rows
    for row in rows:
#         cur.execute("select * from k_user where uid="+str(row['uid']))
#         users = cur.fetchall()                    
#         user =users[0]
#         if(user['water_type']==2):
#             sql = " update k_user_cash_record set discount_num="+str(row['fs'])+" where uid="+str(row['uid'])+" and cash_do_type = 3 and souce_id  = "+str(row['id']);
#             cur.execute(sql)
        #开始结算选一
        str1=unicode('选一','utf-8')
        if (row['mingxi_1']==str1):
            mingxi = int(row['mingxi_2'])
            if(mingxi in hm):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第二球
        str2=unicode('选二','utf-8')
        if (row['mingxi_1']==str2):
            tz =  row['mingxi_2'].split(",")
            tz[0] = int(tz[0])
            tz[1] = int(tz[1])
            if((tz[0] in hm) and  ( tz[1] in hm)):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第三球
        str3=unicode('选三','utf-8')
        if (row['mingxi_1']==str3):
            tz =  row['mingxi_2'].split(",")
            tz[0] = int(tz[0])
            tz[1] = int(tz[1])
            tz[2] = int(tz[2])
            i = 0
            for value in tz:
                if(int(value) in hm):
                    i+=1
            if(i==2):
                temp = row['mingxi_3'].split(",")
                date = temp[1].split(":")
                odd = Decimal(date[1])
                win = row['money']*odd
                mysql="update c_bet set win="+str(win)+",odds="+str(odd)+" where id="+str(row['id'])
                row['win']=win
                cur.execute(mysql)
                con.commit()
                time.sleep(1.5)
                commonOne.win_cbet(row)
            elif(i==3):
                commonOne.win_cbet(row)                    
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)   
        #开始结算第四球
        str4=unicode('选四','utf-8')
        if(row['mingxi_1']==str4): 
            tz = row['mingxi_2'].split(",")   
            tz[0] = int(tz[0])
            tz[1] = int(tz[1])
            tz[2] = int(tz[2])
            tz[3] = int(tz[3])
            i = 0
            for value in tz:
                if(int(value) in hm):
                    i+=1
            if(i==2):
                temp = row['mingxi_3'].split(",")
                date = temp[2].split(":")
                odd = Decimal(date[1])
                win = row['money']*odd
                row['win']=win
                mysql="update c_bet set win="+str(win)+",odds="+str(odd)+" where id="+str(row['id'])
                cur.execute(mysql)
                con.commit()
                time.sleep(1.5)
                commonOne.win_cbet(row)
            elif(i==3):
                temp = row['mingxi_3'].split(",")
                date = temp[1].split(":")
                odd = Decimal(date[1])
                win = row['money']*odd
                row['win']=win
                mysql="update c_bet set win="+str(win)+",odds="+str(odd)+" where id="+str(row['id'])
                cur.execute(mysql)
                con.commit()
                time.sleep(1.5)
                commonOne.win_cbet(row) 
            elif(i==4):
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)                    
        #开始结算第五球
        str5=unicode('选五','utf-8')
        if(row['mingxi_1']==str5):
            tz = row['mingxi_2'].split(",")   
            tz[0] = int(tz[0])
            tz[1] = int(tz[1])
            tz[2] = int(tz[2])
            tz[3] = int(tz[3]) 
            tz[4] = int(tz[4])              
            i = 0
            for value in tz:
                if(int(value) in hm):
                    i+=1
            if(i==3):
                temp = row['mingxi_3'].split(",")
                date = temp[2].split(":")
                odd = Decimal(date[1])
                win = row['money']*odd
                row['win']=win
                mysql="update c_bet set win="+str(win)+",odds="+str(odd)+" where id="+str(row['id'])
                cur.execute(mysql)
                con.commit()
                time.sleep(1.5)
                commonOne.win_cbet(row)
            elif(i==4):
                temp = row['mingxi_3'].split(",")
                date = temp[1].split(":")
                odd = Decimal(date[1])
                win = row['money']*odd
                row['win']=win
                mysql="update c_bet set win="+str(win)+",odds="+str(odd)+" where id="+str(row['id'])
                cur.execute(mysql)
                con.commit()
                time.sleep(1.5)
                commonOne.win_cbet(row)
            elif(i==5):
                commonOne.win_cbet(row)
            else:
                commonOne.lost_cbet(row)
        #开始结算第六球
        strhz=unicode('和值','utf-8')
        if(row['mingxi_1']==strhz):
            ds        = commonOne.Kl8_Auto(hm , 1)
            dx        = commonOne.Kl8_Auto(hm , 2)
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==ds or row['mingxi_2']==dx):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        strhz=unicode('上中下','utf-8')            
        if(row['mingxi_1']==strhz):
            szx        = commonOne.Kl8_Auto(hm , 3)
            szx=unicode(szx,'utf-8')
            if(row['mingxi_2']==szx):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第八名
        strhz=unicode('奇和偶','utf-8')  
        if(row['mingxi_1']==strhz):
            qho        = commonOne.Kl8_Auto(hm , 4)
            qho=unicode(qho,'utf-8')
            if(row['mingxi_2']==qho):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
                                                  
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )                     
    try:
        mysql="update c_auto_8 set ok=1 where qishu="+qishu
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
if __name__ == '__main__':
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )   
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    mysql="update c_auto_8 set ok=0 where ok=2 order by id desc limit 10"
    cur.execute(mysql) 
    con.commit()
    cur.execute("select * from c_auto_8 where ok=0 order by id desc limit 1 ")
    rs = cur.fetchall()
    if rs:
        mysql="update c_auto_8 set ok=2 where qishu="+str(rs[0]['qishu'])
        cur.execute(mysql)
        con.commit()
        try:
            balance8(str(rs[0]['qishu']))  
        finally:
            if con:
                con.close() 
#end of model