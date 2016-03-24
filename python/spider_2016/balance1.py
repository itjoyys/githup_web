'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:balance1.py
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

def balance1(qishu):   
#   获取开奖号码    
    print qishu
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' ) 
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )  
    cur.execute("select * from gd_ten_auto where qishu = '"+qishu+"' ")
    rs = cur.fetchall()
    hm  = [rs[0]['ball_1'],rs[0]['ball_2'],rs[0]['ball_3'],rs[0]['ball_4'],rs[0]['ball_5'],rs[0]['ball_6'],rs[0]['ball_7'],rs[0]['ball_8']]
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from c_bet where type='广东快乐十分' and js=0 and qishu='"+qishu+"' order by addtime asc")
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
            ds        = commonOne.G10_Ds(rs[0]['ball_1'])
            dx        = commonOne.G10_Dx(rs[0]['ball_1'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_1'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_1'])
            fw        = commonOne.G10_Fw(rs[0]['ball_1'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_1'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_1']) or row['mingxi_2']==ds or row['mingxi_2']== dx or row['mingxi_2']==wsdx or row['mingxi_2']== hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第一球开奖号码，则视为中奖

                #$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第二球
        str2=unicode('第二球','utf-8')
        if(row['mingxi_1']==str2):
            ds        = commonOne.G10_Ds(rs[0]['ball_2'])
            dx        = commonOne.G10_Dx(rs[0]['ball_2'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_2'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_2'])
            fw        = commonOne.G10_Fw(rs[0]['ball_2'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_2'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_2']) or row['mingxi_2']==ds or row['mingxi_2']==dx or row['mingxi_2']==wsdx or row['mingxi_2']==hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第三球
        str3=unicode('第三球','utf-8')
        if(row['mingxi_1']==str3):
            ds        = commonOne.G10_Ds(rs[0]['ball_3'])
            dx        = commonOne.G10_Dx(rs[0]['ball_3'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_3'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_3'])
            fw        = commonOne.G10_Fw(rs[0]['ball_3'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_3'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_3']) or row['mingxi_2']==ds or row['mingxi_2']==dx or row['mingxi_2']==wsdx or row['mingxi_2']==hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第四球
        str4=unicode('第四球','utf-8')
        if(row['mingxi_1']==str4):
            ds        = commonOne.G10_Ds(rs[0]['ball_4'])
            dx        = commonOne.G10_Dx(rs[0]['ball_4'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_4'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_4'])
            fw        = commonOne.G10_Fw(rs[0]['ball_4'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_4'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_4']) or row['mingxi_2']==ds or row['mingxi_2']==dx or row['mingxi_2']==wsdx or row['mingxi_2']==hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第五球
        str5=unicode('第五球','utf-8')
        if(row['mingxi_1']==str5):
            ds        = commonOne.G10_Ds(rs[0]['ball_5'])
            dx        = commonOne.G10_Dx(rs[0]['ball_5'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_5'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_5'])
            fw        = commonOne.G10_Fw(rs[0]['ball_5'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_5'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_5']) or row['mingxi_2']==ds or row['mingxi_2']==dx or row['mingxi_2']==wsdx or row['mingxi_2']==hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第六球
        str6=unicode('第六球','utf-8')
        if(row['mingxi_1']==str6):
            ds        = commonOne.G10_Ds(rs[0]['ball_6'])
            dx        = commonOne.G10_Dx(rs[0]['ball_6'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_6'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_6'])
            fw        = commonOne.G10_Fw(rs[0]['ball_6'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_6'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_6']) or row['mingxi_2']==ds or row['mingxi_2']==dx or row['mingxi_2']==wsdx or row['mingxi_2']==hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第七球
        str7=unicode('第七球','utf-8')
        if(row['mingxi_1']==str7):
            ds        = commonOne.G10_Ds(rs[0]['ball_7'])
            dx        = commonOne.G10_Dx(rs[0]['ball_7'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_7'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_7'])
            fw        = commonOne.G10_Fw(rs[0]['ball_7'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_7'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_7']) or row['mingxi_2']==ds or row['mingxi_2']==dx or row['mingxi_2']==wsdx or row['mingxi_2']==hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算第八球
        str8=unicode('第八球','utf-8')
        if(row['mingxi_1']==str8):
            ds        = commonOne.G10_Ds(rs[0]['ball_8'])
            dx        = commonOne.G10_Dx(rs[0]['ball_8'])
            wsdx    = commonOne.G10_WsDx(rs[0]['ball_8'])
            hsds    = commonOne.G10_HsDs(rs[0]['ball_8'])
            fw        = commonOne.G10_Fw(rs[0]['ball_8'])
            zfb    = commonOne.G10_Zfb(rs[0]['ball_8'])
            ds=unicode(ds,'utf-8')
            dx=unicode(dx,'utf-8')
            wsdx=unicode(wsdx,'utf-8')
            hsds=unicode(hsds,'utf-8')
            fw=unicode(fw,'utf-8')
            zfb=unicode(zfb,'utf-8')
            if(row['mingxi_2']==str(rs[0]['ball_8']) or row['mingxi_2']==ds or row['mingxi_2']==dx or row['mingxi_2']==wsdx or row['mingxi_2']==hsds or row['mingxi_2']==fw or row['mingxi_2']== zfb):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
#         开始结算总和大小
        strd=unicode('总和大','utf-8')
        strx=unicode('总和小','utf-8')
        if(row['mingxi_2']==strd or row['mingxi_2']==strx):
            zhdx = commonOne.G10_Auto(hm,2)
            zhdx=unicode(zhdx,'utf-8')
            strhe=unicode('和','utf-8') 
            if(zhdx==strhe):
                #如果投注内容等于第一球开奖号码，则视为中奖
                commonOne.invalid_cbet(row)
            elif(row['mingxi_2']==zhdx):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算总和单双
        strd=unicode('总和单','utf-8')
        strx=unicode('总和双','utf-8')
        if(row['mingxi_2']==strd or row['mingxi_2']==strx):
            zhds = commonOne.G10_Auto(hm,3)
            zhds=unicode(zhds,'utf-8')
            if(row['mingxi_2']==zhds):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算总和尾数大小
        strl=unicode('总和尾大','utf-8')
        strh=unicode('总和尾小','utf-8') 
        if(row['mingxi_2']==strl or row['mingxi_2']==strh):
            zhwsdx = commonOne.G10_Auto(hm,4)
            zhwsdx=unicode(zhwsdx,'utf-8')
            if(row['mingxi_2']==zhwsdx):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #开始结算龙虎
        strl=unicode('龙','utf-8')
        strh=unicode('虎','utf-8') 
        if(row['mingxi_2']==strl or row['mingxi_2']==strh):
            lh = commonOne.G10_Auto(hm,5) 
            lh = unicode(lh,'utf-8')               
            if(row['mingxi_2']==lh):
                #如果投注内容等于第二球开奖号码，则视为中奖
                commonOne.win_cbet(row)
            else:
                #注单未中奖，修改注单内容
                commonOne.lost_cbet(row)
        #连码
        str1=unicode('任选二','utf-8')   
        str2=unicode('任选二组','utf-8')   
        str3=unicode('任选三','utf-8')   
        str4=unicode('任选四','utf-8')   
        str5=unicode('任选五','utf-8')
        if(row['mingxi_1']==str1 or row['mingxi_1']==str2 or row['mingxi_1']==str3 or row['mingxi_1']==str4 or row['mingxi_1']==str5):
            status = commonOne.G10_nextNum(hm,row['mingxi_1'],row['mingxi_2']) 
            if(status == 1):
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
        mysql="update gd_ten_auto set ok=1 where qishu="+qishu
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
        
if __name__ == '__main__':
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )   
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
    mysql="update gd_ten_auto set ok=0 where ok=2 order by id desc limit 10 "
    cur.execute(mysql) 
    con.commit()
    cur.execute("select * from gd_ten_auto where ok =0 order by id desc limit 1 ")
    rs = cur.fetchall()
    if rs:
        mysql="update gd_ten_auto set ok=2 where qishu="+'%d'%rs[0]['qishu']
        cur.execute(mysql)
        con.commit()
        try:
            balance1('%d'%rs[0]['qishu'])  
        finally:
            if con:
                con.close()  
#end of model