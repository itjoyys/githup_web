'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:balance3.py
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

def balance3(qishu):   
    
#   获取开奖号码    
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from bj_10_auto where qishu = '"+qishu+"' ")
    rs = cur.fetchall()
    hm  = [rs[0]['ball_1'],rs[0]['ball_2'],rs[0]['ball_3'],rs[0]['ball_4'],rs[0]['ball_5'],rs[0]['ball_6'],rs[0]['ball_7'],rs[0]['ball_8'],rs[0]['ball_9'],rs[0]['ball_10']]
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from c_bet where type='北京赛车PK拾' and js=0 and qishu='"+qishu+"' order by addtime asc")
    rows = cur.fetchall()
    for row in rows:
#         cur.execute("select * from k_user where uid="+str(row['uid']))
#         users = cur.fetchall()                    
#         user =users[0]
#         if(user['water_type']==2):
#             sql = " update k_user_cash_record set discount_num="+str(row['fs'])+" where uid="+str(row['uid'])+" and cash_do_type = 3 and souce_id  = "+str(row['id']);
#             cur.execute(sql) 
        #开始结算第一球
        str1=unicode('冠军','utf-8')
        if (row['mingxi_1']==str1):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_1'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_1'])
            lh        = commonOne.Pk10_Auto(hm , 4 , 0)
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            lh=unicode(lh,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_1']) or row['mingxi_2']==ds or row['mingxi_2']== dx or row['mingxi_2']==lh):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第二球
        str2=unicode('亚军','utf-8')
        if (row['mingxi_1']==str2):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_2'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_2'])
            lh        = commonOne.Pk10_Auto(hm , 5 , 0)
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            lh=unicode(lh,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_2']) or row['mingxi_2']==ds or row['mingxi_2']== dx or row['mingxi_2']==lh):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row) 
        #开始结算第三球
        str3=unicode('第三名','utf-8')
        if (row['mingxi_1']==str3):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_3'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_3'])
            lh        = commonOne.Pk10_Auto(hm , 6 , 0)
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            lh=unicode(lh,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_3']) or row['mingxi_2']==ds or row['mingxi_2']== dx or row['mingxi_2']==lh):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)  
        #开始结算第四球
        str4=unicode('第四名','utf-8')
        if(row['mingxi_1']==str4):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_4'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_4'])
            lh        = commonOne.Pk10_Auto(hm , 7 , 0)
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            lh=unicode(lh,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_4']) or row['mingxi_2']==ds or row['mingxi_2']== dx or row['mingxi_2']==lh):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第五球
        str5=unicode('第五名','utf-8')
        if(row['mingxi_1']==str5):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_5'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_5'])
            lh        = commonOne.Pk10_Auto(hm , 8 , 0)
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            lh=unicode(lh,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_5']) or row['mingxi_2']==ds or row['mingxi_2']== dx or row['mingxi_2']==lh):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第六球
        str6=unicode('第六名','utf-8')
        if(row['mingxi_1']==str6):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_6'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_6'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_6']) or row['mingxi_2']==ds or row['mingxi_2']== dx):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第七名
        str7=unicode('第七名','utf-8')
        if(row['mingxi_1']==str7):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_7'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_7'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_7']) or row['mingxi_2']==ds or row['mingxi_2']== dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row) 
        #开始结算第八名
        str8=unicode('第八名','utf-8')
        if(row['mingxi_1']==str8):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_8'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_8'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_8']) or row['mingxi_2']==ds or row['mingxi_2']== dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
                    
        #开始结算第九名
        str9=unicode('第九名','utf-8')
        if(row['mingxi_1']==str9):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_9'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_9'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_9']) or row['mingxi_2']==ds or row['mingxi_2']== dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第十名
        str10=unicode('第十名','utf-8')
        if(row['mingxi_1']==str10):
            ds        = commonOne.Pk10_Auto(hm , 10 , rs[0]['ball_10'])
            dx        = commonOne.Pk10_Auto(hm , 9 , rs[0]['ball_10'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_10']) or row['mingxi_2']==ds or row['mingxi_2']== dx ):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算冠亚军和
        strhe=unicode('冠亚军和','utf-8')
        if(row['mingxi_1']==strhe):
            zh        = commonOne.Pk10_Auto(hm , 1 , 0)
            dx        = commonOne.Pk10_Auto(hm , 2 , 0)
            ds        = commonOne.Pk10_Auto(hm , 3 , 0)
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            if(row['mingxi_2']==str(zh) or row['mingxi_2']==ds or row['mingxi_2']==dx):
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)    
        #开始结算龙虎
        strhe=unicode('龍虎','utf-8')
        if(row['mingxi_1']==strhe):
            strhe4=unicode('1V10 龍虎','utf-8')
            if( row['mingxi_3']==strhe4):
                zh = commonOne.Pk10_Auto(hm , 4 , 0)
                zh = unicode(zh,'utf-8')
                if(row['mingxi_2']==zh):
                    commonOne.win_cbet(row)
                else:
                    #注单未中奖，修改注单内容
                    commonOne.lost_cbet(row)
            strhe4=unicode('2V9 龍虎','utf-8')
            if( row['mingxi_3']==strhe4):
                zh = commonOne.Pk10_Auto(hm , 5 , 0)
                zh = unicode(zh,'utf-8')
                if(row['mingxi_2']==zh):
                    commonOne.win_cbet(row)
                else:
                    #注单未中奖，修改注单内容
                    commonOne.lost_cbet(row)    
            strhe4=unicode('3V8 龍虎','utf-8')
            if( row['mingxi_3']==strhe4):
                zh = commonOne.Pk10_Auto(hm , 6 , 0)
                zh = unicode(zh,'utf-8')
                if(row['mingxi_2']==zh):
                    commonOne.win_cbet(row)
                else:
                    #注单未中奖，修改注单内容
                    commonOne.lost_cbet(row)    
            strhe4=unicode('4V7 龍虎','utf-8')
            if( row['mingxi_3']==strhe4):
                zh = commonOne.Pk10_Auto(hm , 7 , 0)
                zh = unicode(zh,'utf-8')
                if(row['mingxi_2']==zh):
                    commonOne.win_cbet(row)
                else:
                    #注单未中奖，修改注单内容
                    commonOne.lost_cbet(row) 
            strhe4=unicode('5V6 龍虎','utf-8')
            if( row['mingxi_3']==strhe4):
                zh = commonOne.Pk10_Auto(hm , 8 , 0)
                zh = unicode(zh,'utf-8')
                if(row['mingxi_2']==zh):
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
        mysql="update bj_10_auto set ok=1 where qishu="+qishu
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
if __name__ == '__main__':
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )   
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    mysql="update bj_10_auto set ok=0 where ok=2 order by id desc limit 10"
    cur.execute(mysql)
    con.commit() 
    cur.execute("select * from bj_10_auto where ok=0 order by id desc limit 1 ")
    rs = cur.fetchall()
    if rs:
        mysql="update bj_10_auto set ok=2 where qishu="+str(rs[0]['qishu'])
        cur.execute(mysql)
        con.commit()
        try:
            balance3(str(rs[0]['qishu']))  
        finally:
            if con:
                con.close() 
#end of model