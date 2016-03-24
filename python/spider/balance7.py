'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:balance7.py
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

def balance7(qishu):   
    
#   获取开奖号码    
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    cur.execute("select * from c_auto_7 where nn = '"+qishu+"' ")
    rs = cur.fetchall()
    hm  = [rs[0]['n1'],rs[0]['n2'],rs[0]['n3'],rs[0]['n4'],rs[0]['n5'],rs[0]['n6'],rs[0]['na'],rs[0]['x1'],rs[0]['x2'],rs[0]['x3'],rs[0]['x4'],rs[0]['x5'],rs[0]['x6'],rs[0]['sx']]
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    cur.execute("select * from c_bet where type='六合彩' and js=0 and qishu='"+qishu+"' order by addtime asc")
    rows = cur.fetchall()
    for row in rows:
#         cur.execute("select * from k_user where uid="+str(row['uid']))
#         users = cur.fetchall()                    
#         user =users[0]
#         if(user['water_type']==2):
#             sql = " update k_user_cash_record set discount_num="+str(row['fs'])+" where uid="+str(row['uid'])+" and cash_do_type = 3 and souce_id  = "+str(row['id']);
#             cur.execute(sql)
        #开始结算选一
        str1=unicode('特码','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],1)
        str1=unicode('正码','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],2)
        str1=unicode('正特','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],3)
        str1=unicode('正1-6','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],4)
        str1=unicode('过关','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],5)
        str1=unicode('连码','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],6,row['money'],row['id'])
            connew = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
            curnew = connew.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
            curnew.execute("select * from c_bet where id="+str(row['id']))
            data = curnew.fetchall()
            row = data[0]
        str1=unicode('半波','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],7)
        str1=unicode('尾数','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],9)
        str1=unicode('生肖','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],10)
        str1=unicode('生肖连','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],11)
        str1=unicode('尾数连','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],12)
        str1=unicode('全不中','utf-8')
        if (row['mingxi_1']==str1):
            status = commonOne.lhc(hm,row['mingxi_2'],row['mingxi_3'],13)
        if(status==1):               
            #如果投注内容等于第二球开奖号码，则视为中奖
            commonOne.win_cbet(row)
        elif(status==3):
            #如果投注内容等于第一球开奖号码，则视为中奖
            commonOne.invalid_cbet(row)
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
        mysql="update c_auto_7 set ok=1 where nn="+qishu
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
if __name__ == '__main__':
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )   
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    mysql="update c_auto_7 set ok=0 where ok=2 order by id desc limit 10"
    cur.execute(mysql)
    con.commit() 
    cur.execute("select * from c_auto_7 where ok=0 order by id desc limit 1 ")
    rs = cur.fetchall()
    if rs:
        mysql="update c_auto_7 set ok=2 where nn="+str(rs[0]['nn'])
        cur.execute(mysql)
        con.commit()
        try:
            balance7(str(rs[0]['nn']))  
        finally:
            if con:
                con.close() 
#end of model
