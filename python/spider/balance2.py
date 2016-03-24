'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:balance2.py
# -*- coding: utf-8 -*-
#encoding=utf-8


import MySQLdb
import commonOne
import chardet
import time
import globalConfig
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_USER_P = globalConfig.DB_USER_P
DB_PASSWD_P = globalConfig.DB_PASSWD_P
DB_NAME = globalConfig.DB_NAME
DB_NAME_PRI = globalConfig.DB_NAME_P   
#
#

def balance2(qishu):    
#   获取开奖号码    
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from c_auto_2 where qishu = '"+qishu+"' ")
    rs = cur.fetchall()
    hm  = [rs[0]['ball_1'],rs[0]['ball_2'],rs[0]['ball_3'],rs[0]['ball_4'],rs[0]['ball_5']]
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from c_bet where type='重庆时时彩' and js=0 and qishu='"+qishu+"' order by addtime asc")
    print "select * from c_bet where type='重庆时时彩' and js=0 and qishu='"+qishu+"' order by addtime asc"
    rows = cur.fetchall()
    print rows
    sum = len(rows)
    for row in rows:
#         cur.execute("select * from k_user where uid="+str(row['uid']))
#         users = cur.fetchall()                    
#         user =users[0]
#         if(user['water_type']==2):
#             sql = " update k_user_cash_record set discount_num="+str(row['fs'])+" where uid="+str(row['uid'])+" and cash_do_type = 3 and souce_id  = "+str(row['id']);
#             cur.execute(sql) 
        #开始结算第一球
        str1=unicode('第一球','utf-8')
        if (row['mingxi_1']==str1):
            ds        = commonOne.Ssc_Ds(rs[0]['ball_1'])
            dx        = commonOne.Ssc_Dx(rs[0]['ball_1'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_1']) or row['mingxi_2']==ds or row['mingxi_2']== dx ):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第二球
        str2=unicode('第二球','utf-8')
        if(row['mingxi_1']==str2):
            ds        = commonOne.Ssc_Ds(rs[0]['ball_2'])
            dx        = commonOne.Ssc_Dx(rs[0]['ball_2'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_2']) or row['mingxi_2']==ds or row['mingxi_2']== dx ):               
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第三球
        str3=unicode('第三球','utf-8')
        if(row['mingxi_1']==str3):
            ds        = commonOne.Ssc_Ds(rs[0]['ball_3'])
            dx        = commonOne.Ssc_Dx(rs[0]['ball_3'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_3']) or row['mingxi_2']==ds or row['mingxi_2']==dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第四球
        str4=unicode('第四球','utf-8')
        if(row['mingxi_1']==str4):
            ds        = commonOne.Ssc_Ds(rs[0]['ball_4'])
            dx        = commonOne.Ssc_Dx(rs[0]['ball_4'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_4']) or row['mingxi_2']==ds or row['mingxi_2']==dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)  
        #开始结算第五球
        str5=unicode('第五球','utf-8')
        if(row['mingxi_1']==str5):
            ds        = commonOne.Ssc_Ds(rs[0]['ball_5'])
            dx        = commonOne.Ssc_Dx(rs[0]['ball_5'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_5']) or row['mingxi_2']==ds or row['mingxi_2']==dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
#         开始结算总和大小
        strd=unicode('总和大','utf-8')
        strx=unicode('总和小','utf-8')
        if(row['mingxi_2']==strd or row['mingxi_2']==strx):
            zonghe = commonOne.Ssc_Auto(hm,2)
            zonghe=unicode(zonghe,'utf-8')
            if(row['mingxi_2']==zonghe):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)         
        #开始结算总和单双
        strd=unicode('总和单','utf-8')
        strx=unicode('总和双','utf-8')
        if(row['mingxi_2']==strd or row['mingxi_2']==strx):
            zonghe = commonOne.Ssc_Auto(hm,3)
            zonghe=unicode(zonghe,'utf-8')
            if(row['mingxi_2']==zonghe):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算龙虎和
        strl=unicode('龙','utf-8')
        strh=unicode('虎','utf-8') 
        strhh=unicode('和','utf-8')        
        if(row['mingxi_2']==strl or row['mingxi_2']==strh or row['mingxi_2']==strhh):
            longhu = commonOne.Ssc_Auto(hm,4)
            longhu=unicode(longhu,'utf-8') 
            if(row['mingxi_2']==longhu):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
            #开始结算前三
        strqs=unicode('前三','utf-8') 
        if(row['mingxi_1']==strqs):
            qiansan = commonOne.Ssc_Auto(hm,5)
            qiansan=unicode(qiansan,'utf-8') 
            if(row['mingxi_2']==qiansan):
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算中三
        strqzs=unicode('中三','utf-8') 
        if(row['mingxi_1']==strqzs):
            zhongsan = commonOne.Ssc_Auto(hm,6)
            zhongsan = unicode(zhongsan,'utf-8')             
            if(row['mingxi_2']==zhongsan):
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算后三
        strhs=unicode('后三','utf-8') 
        if(row['mingxi_1']==strhs):
            housan = commonOne.Ssc_Auto(hm,7)
            housan=unicode(housan,'utf-8')
            if(row['mingxi_2']==housan):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)  
        #开始结算牛牛
        strdn=unicode('斗牛','utf-8') 
        if(row['mingxi_1']==strdn):
            if(row['mingxi_2'] in ['牛大','牛小']):
                housan = commonOne.Ssc_Auto(hm,8,1)
            elif(row['mingxi_2'] in ['牛单','牛双']):
                housan = commonOne.Ssc_Auto(hm,8,2)
            else:
                housan = commonOne.Ssc_Auto(hm,8)
            housan=unicode(housan,'utf-8') 
            if(row['mingxi_2']==housan):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row) 
        #开始结算梭哈
        strsh=unicode('梭哈','utf-8')
        if(row['mingxi_1']==strsh):
            typeall={'五条':1,'四条':2,'葫芦':3,'顺子':4,'三条':5,'两对':6,'一对':7,'散号':8}
            housan = commonOne.Ssc_Auto(hm,9,typeall[str(row['mingxi_2'])])
            housan=unicode(housan,'utf-8')
            if(row['mingxi_2']==housan):
                #如果投注内容等于第一球开奖号码，则视为中奖
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
        mysql="update c_auto_2 set ok=1 where qishu="+qishu
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
if __name__ == '__main__':
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )   
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    mysql="update c_auto_2 set ok=0 where ok=2 order by id desc limit 10"
    cur.execute(mysql) 
    con.commit()
    cur.execute("select * from c_auto_2 where ok=0 order by id desc limit 1 ")
    rs = cur.fetchall()
    if rs:
        mysql="update c_auto_2 set ok=2 where qishu="+str(rs[0]['qishu'])
        cur.execute(mysql)
        con.commit()
        try:
            balance2(str(rs[0]['qishu']))  
        finally:
            if con:
                con.close() 
#end of model