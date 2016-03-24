'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:balance5.py
# -*- coding: utf-8 -*-
#encoding=utf-8


import MySQLdb
import commonOne
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

def balance5(qishu):   
    
#   获取开奖号码    
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    cur.execute("select * from fc_3d_auto where qishu = '"+qishu+"' ")
    rs = cur.fetchall()
    hm  = [rs[0]['ball_1'],rs[0]['ball_2'],rs[0]['ball_3']]
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    cur.execute("select * from c_bet where type='福彩3D' and js=0 and qishu='"+qishu+"' order by addtime asc")
    rows = cur.fetchall()
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
            ds        = commonOne.FC3D_Auto(hm,4)#单双
            dx        = commonOne.FC3D_Auto(hm,1)#大小
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
            ds        = commonOne.FC3D_Auto(hm,5)#单双
            dx        = commonOne.FC3D_Auto(hm,2)#大小
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
            ds        = commonOne.FC3D_Auto(hm,6)#单双
            dx        = commonOne.FC3D_Auto(hm,3)#大小
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_3']) or row['mingxi_2']==ds or row['mingxi_2']==dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算独胆
        bd=unicode('独胆','utf-8')
        if(row['mingxi_1']==bd):
            if(row['mingxi_2']==str(rs[0]['ball_1']) or row['mingxi_2']==str(rs[0]['ball_2']) or row['mingxi_2']==str(rs[0]['ball_3'])):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算独胆
        kd=unicode('跨度','utf-8')
        if(row['mingxi_1']==kd):
            numSpan=max(abs(rs[0]['ball_1']-rs[0]['ball_2']),abs(rs[0]['ball_1']-rs[0]['ball_3']),abs(rs[0]['ball_2']-rs[0]['ball_3']))
            if(row['mingxi_2']==str(numSpan)):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
#         开始结算总和大小
        strhd=unicode('总和大','utf-8')
        strhx=unicode('总和小','utf-8')
        if(row['mingxi_2']==strhd or row['mingxi_2']==strhx):
            zonghe = commonOne.FC3D_Auto(hm,7)
            zonghe=unicode(zonghe,'utf-8')
            if(row['mingxi_2']==zonghe):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)           
        #开始结算总和单双
        strhd=unicode('总和单','utf-8')
        strhs=unicode('总和双','utf-8')
        if(row['mingxi_2']==strhd or row['mingxi_2']==strhs):
            zonghe = commonOne.FC3D_Auto(hm,8)
            zonghe = unicode(zonghe,'utf-8')
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
            longhu = commonOne.FC3D_Auto(hm,9)
            longhu=unicode(longhu,'utf-8')
            if(row['mingxi_2']==longhu):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算3连
        strsl=unicode('3连','utf-8')
        if(row['mingxi_1']==strsl):
            qiansan = commonOne.FC3D_Auto(hm,10)
            qiansan = unicode(qiansan,'utf-8')
            if(row['mingxi_2']==qiansan):
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
        mysql="update fc_3d_auto set ok=1 where qishu="+qishu
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
if __name__ == '__main__':
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )   
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    mysql="update fc_3d_auto set ok=0 where ok=2 order by id desc limit 10"
    cur.execute(mysql) 
    con.commit()
    cur.execute("select * from fc_3d_auto where ok=0  order by id desc limit 1 ")
    rs = cur.fetchall()
    if rs:
        mysql="update fc_3d_auto set ok=2 where qishu="+str(rs[0]['qishu'])
        print mysql
        cur.execute(mysql)
        con.commit()
        try:
            balance5(str(rs[0]['qishu']))  
        finally:
            if con:
                con.close() 
#end of model