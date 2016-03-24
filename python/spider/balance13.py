'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:balance13.py
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

def balance13(qishu):    
#   获取开奖号码    
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from c_auto_13 where qishu = '"+qishu+"' ")
    rs = cur.fetchall()
    hm  = [rs[0]['ball_1'],rs[0]['ball_2'],rs[0]['ball_3']]
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from c_bet where type='江苏快3' and js=0 and qishu='"+qishu+"' order by addtime asc")
    print "select * from c_bet where type='江苏快3' and js=0 and qishu='"+qishu+"' order by addtime asc"
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
        str1=unicode('和值','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.kuai3(hm,row['mingxi_2'],1)      
        str1=unicode('独胆','utf-8')
        if(row['mingxi_1']==str1):
            status = commonOne.kuai3(hm,row['mingxi_2'],3)              
        str1=unicode('豹子','utf-8')
        if(row['mingxi_1']==str1):
            status = commonOne.kuai3(hm,row['mingxi_2'],4)                
        str1=unicode('两连','utf-8')
        if(row['mingxi_1']==str1):
            status = commonOne.kuai3(hm,row['mingxi_2'],5)                
        str1=unicode('对子','utf-8')
        if(row['mingxi_1']==str1): 
            status = commonOne.kuai3(hm,row['mingxi_2'],6)
        if(status==1):               
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
        mysql="update c_auto_13 set ok=1 where qishu="+qishu
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
if __name__ == '__main__':
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' ) 
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    mysql="update c_auto_13 set ok=0 where ok=2 order by id desc limit 10"
    cur.execute(mysql) 
    con.commit() 
    cur.execute("select * from c_auto_13 where ok=0 order by id desc limit 1 ")
    rs = cur.fetchall()
    if rs:
        mysql="update c_auto_13 set ok=2 where qishu="+str(rs[0]['qishu'])
        cur.execute(mysql)
        con.commit()
        try:
            balance13(str(rs[0]['qishu']))  
        finally:
            if con:
                con.close() 
#end of model