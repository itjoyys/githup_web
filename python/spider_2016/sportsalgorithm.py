'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:sportsalgorithm.py
# -*- coding: utf-8 -*-
#encoding=utf-8


import MySQLdb
import string
import globalConfig
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
#
#

# 单式赢
def win_ds(bet, mb_inball, tg_inball):
    #获取反水比例
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
#     mysql="select k_group.zqfsbl from k_user left join k_group on k_group.id=k_user.gid where k_user.uid="+'%d'%bet['uid']
#     cur.execute(mysql)
#     fsbl = cur.fetchall()
#     fsbl =fsbl[0]['zqfsbl']
    fsbl = 0
    #结算
    try:
        #注单中奖，给会员账户增加奖金
        win_money = bet['bet_win'] + bet['bet_money'] * fsbl
        mysql="update k_bet set win=bet_win , status=1 ,is_jiesuan=1 , update_time=now() , MB_Inball = '"+mb_inball+"' , TG_Inball ='"+tg_inball+"' , fs ="+str(bet['bet_money'] * fsbl)+" where bid="+'%d'%bet['bid']+" and status=0 "
        cur.execute(mysql)

        mysql="update k_user set money=money+"+str(win_money)+" where uid='"+str(bet['uid'])+"'"
        print 'aaaaa'
        cur.execute(mysql)
        cur.execute("select * from k_user where uid="+str(bet['uid']))
        users = cur.fetchall()                    
        user =users[0]
        print 'ccceeee'
        if():
            sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+str(user['site_id'])+"','"+str(bet['bid'])+"','"+str(user['username'])+"','"+str(bet['uid'])+"','"+str(bet['agent_id'])+"',15,1,'"+str(bet['bet_win'])+"',"+str(user['money'])+",now(),"+unicode("'自动结算注单：",'utf-8')+str(bet['number'])+unicode(" 類型：",'utf-8')+str(bet['ball_sort'])+"',0,'"+str(user['index_id'])+"') "
        else:
            sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+str(user['site_id'])+"','"+str(bet['bid'])+"','"+str(user['username'])+"','"+str(bet['uid'])+"','"+str(bet['agent_id'])+"',15,1,'"+str(win_money)+"',"+str(user['money'])+",now(),"+unicode("'自动结算注单：",'utf-8')+str(bet['number'])+unicode(" 類型：",'utf-8')+str(bet['ball_sort'])+"',0,'"+str(user['index_id'])+"') "            
        cur.execute(sql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "注单修改失败!!!"+str(bet['bid'])
    con.commit()
    cur.close()     
    con.close()      

#单式输
def lost_ds(bet, mb_inball, tg_inball):
    #获取反水比例
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
#     mysql="select k_group.zqfsbl from k_user left join k_group on k_group.id=k_user.gid where k_user.uid="+'%d'%bet['uid']
#     cur.execute(mysql)
#     fsbl = cur.fetchall()
#     fsbl =fsbl[0]['zqfsbl']
    fsbl = 0
    #结算
    try:
        mysql="update k_bet set status=2 , update_time=now() ,is_jiesuan=1, MB_Inball = '"+mb_inball+"' , TG_Inball ='"+tg_inball+"' , fs ="+str(bet['bet_money'] * fsbl)+" where bid="+str(bet['bid'])+" and status=0 "
        cur.execute(mysql)
        #注单中奖，给会员账户增加奖金
        win_money = bet['bet_money'] * fsbl
        mysql="update k_user set money=money+"+str(win_money)+" where uid='"+str(bet['uid'])+"'"
        cur.execute(mysql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "注单修改失败!!!"+str(bet['bid'])
    con.commit()
    cur.close()     
    con.close()  

#无效或取消的订单
def invalid_bet_ds(bet, mb_inball, tg_inball):
    #获取反水比例
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
#     mysql="select k_group.zqfsbl from k_user left join k_group on k_group.id=k_user.gid where k_user.uid="+'%d'%bet['uid']
#     cur.execute(mysql)
#     fsbl = cur.fetchall()
#     fsbl =fsbl[0]['zqfsbl']
#    fsbl = 0
    #结算
    try:
        mysql="update k_bet set win=bet_money, status=3 , update_time=now(),is_jiesuan=1 , MB_Inball = '"+mb_inball+"' , TG_Inball ='"+tg_inball+"'  where bid="+str(bet['bid'])+" and status=0 "
        cur.execute(mysql)
        #注单中奖，给会员账户增加奖金
        win_money = bet['bet_money']
        mysql="update k_user set money=money+"+str(win_money)+" where uid='"+str(bet['uid'])+"'"
        cur.execute(mysql)
        cur.execute("select * from k_user where uid="+str(bet['uid']))
        users = cur.fetchall()                    
        user =users[0]                   
        sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+user['site_id']+"','"+str(bet['bid'])+"','"+str(user['username'])+"','"+str(bet['uid'])+"','"+str(bet['agent_id'])+"',22,1,'"+str(win_money)+"',"+str(user['money'])+unicode(",now(),'自动结算单号："+str(bet['number'])+" 類型：",'utf-8')+bet['ball_sort']+"',0,'"+str(user['index_id'])+"') "
        cur.execute(sql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "注单修改失败!!!"+str(bet['bid'])
    con.commit()
    cur.close()     
    con.close() 


#单式赢一半
def halfwin_ds(bet, mb_inball, tg_inball):
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
#     mysql="select k_group.zqfsbl from k_user left join k_group on k_group.id=k_user.gid where k_user.uid="+str(bet['uid'])
#     cur.execute(mysql)
#     fsbl = cur.fetchall()
#     fsbl =fsbl[0]['zqfsbl']
    fsbl = 0
    #结算
    try:
        #注单中奖，给会员账户增加奖金
        if(bet['bet_point']<Decimal(0)):
            #win_money = bet['bet_money'] + bet['bet_win']*Decimal(str(0.5))
            win_money = bet['bet_money'] + (bet['bet_win']-bet['bet_money'])*Decimal(str(0.5))
            mysql="update k_bet set win=bet_money+(bet_win-bet_money)/2, status=4 , is_jiesuan=1 , update_time=now() , MB_Inball = '"+mb_inball+"' , TG_Inball ='"+tg_inball+"'  , fs ="+str(bet['bet_money'] * fsbl/2)+" where bid="+str(bet['bid'])+" and status=0 "
        else:
            win_money = bet['bet_money'] + ((bet['bet_money'] / 2) * bet['bet_point']) + bet['bet_money'] * fsbl / 2        
            mysql="update k_bet set win=bet_money+((bet_money/2)*bet_point) , status=4 , is_jiesuan=1 , update_time=now() , MB_Inball = '"+mb_inball+"' , TG_Inball ='"+tg_inball+"'  , fs ="+str(bet['bet_money'] * fsbl/2)+" where bid="+str(bet['bid'])+" and status=0 "
        cur.execute(mysql)
        mysql="update k_user set money=money+"+str(win_money)+" where uid='"+str(bet['uid'])+"'"
        cur.execute(mysql)
        cur.execute("select * from k_user where uid="+str(bet['uid']))
        users = cur.fetchall()                    
        user =users[0]                   
        sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+user['site_id']+"','"+str(bet['bid'])+"','"+str(user['username'])+"','"+str(bet['uid'])+"','"+str(bet['agent_id'])+"',15,1,'"+str(win_money)+"',"+str(user['money'])+unicode(",now(),'自动结算单号："+str(bet['number'])+" 類型：",'utf-8')+bet['ball_sort']+"',0,'"+str(user['index_id'])+"') "
        cur.execute(sql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "注单修改失败!!!"+str(bet['bid'])
    con.commit()
    cur.close()     
    con.close() 

# 单式输一半
def halflost_ds(bet, mb_inball, tg_inball):
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
#     mysql="select k_group.zqfsbl from k_user left join k_group on k_group.id=k_user.gid where k_user.uid="+str(bet['uid'])
#     cur.execute(mysql)
#     fsbl = cur.fetchall()
#     fsbl =fsbl[0]['zqfsbl']
    fsbl = 0
    fs = bet['bet_money']*fsbl*Decimal(str(0.5))
    #结算
    try:

        win_money = (bet['bet_money'] + (bet['bet_money'] * fsbl)) * Decimal(str(0.5))
        mysql="update k_bet set win=bet_money*0.5 , status=5 , update_time=now() , is_jiesuan=1 , MB_Inball = '"+mb_inball+"' , TG_Inball ='"+tg_inball+"'  , fs ="+str(fs)+" where bid="+str(bet['bid'])+" and status=0 "
        cur.execute(mysql)

        mysql="update k_user set money=money+"+str(win_money)+"  where uid='"+str(bet['uid'])+"'"
        cur.execute(mysql)
        cur.execute("select * from k_user where uid="+str(bet['uid']))
        users = cur.fetchall()                    
        user =users[0]                   
        sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+user['site_id']+"','"+str(bet['bid'])+"','"+str(user['username'])+"','"+str(bet['uid'])+"','"+str(bet['agent_id'])+"',15,1,'"+str(win_money)+"',"+str(user['money'])+unicode(",now(),'自动结算单号："+str(bet['number'])+" 類型：",'utf-8')+bet['ball_sort']+"',0,'"+str(user['index_id'])+"') "
        cur.execute(sql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "注单修改失败!!!"+str(bet['bid'])
    con.commit()
    cur.close()     
    con.close() 

#单式的和局

def tie_bet_ds(bet, mb_inball, tg_inball):
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
#     mysql="select k_group.zqfsbl from k_user left join k_group on k_group.id=k_user.gid where k_user.uid="+str(bet['uid'])
#     cur.execute(mysql)
#     fsbl = cur.fetchall()
#     fsbl =fsbl[0]['zqfsbl']
#    fsbl = 0
    #结算
    try:
        mysql="update k_bet set win=bet_money , status=8 , update_time=now() , is_jiesuan=1 , MB_Inball = '"+mb_inball+"' , TG_Inball ='"+tg_inball+"'  where bid="+str(bet['bid'])+" and status=0 "
        cur.execute(mysql)
        #注单中奖，给会员账户增加奖金
        win_money = bet['bet_money']
        mysql="update k_user set money=money+"+str(win_money)+"  where uid='"+str(bet['uid'])+"'"
        print mysql
        cur.execute(mysql)
        cur.execute("select * from k_user where uid="+str(bet['uid']))
        users = cur.fetchall()                    
        user =users[0]   
        try:                
            sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+user['site_id']+"','"+str(bet['bid'])+"','"+str(user['username'])+"','"+str(bet['uid'])+"','"+str(bet['agent_id'])+"',20,1,'"+str(win_money)+"',"+str(user['money'])+unicode(",now(),'自动结算单号："+str(bet['number'])+" 類型：",'utf-8')+bet['ball_sort']+"',0,'"+str(user['index_id'])+"') "
        except Exception as e:
            print e
            print sql
        cur.execute(sql)
    except MySQLdb.Error, e:
        con.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        print "注单修改失败!!!"+str(bet['bid'])
    con.commit()
    cur.close()     
    con.close() 

#结算串关

def cg_bet(row):
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    #获取对应的组数据
    cur.execute("select * from k_bet_cg_group where status=0  and  gid="+str(row['gid']))
    betcg_group = cur.fetchall()
    if (betcg_group):
        betcg_group = betcg_group[0]
        cur.execute("select status from k_bet_cg where gid="+str(row['gid']))
        betcgs = cur.fetchall()
        
        cg_count = len(betcgs)
        i = k = 0;
        statsarr = [1, 3, 4, 5, 8]
        statsarrp=[3, 8]
        for betcg in betcgs:
            if ( betcg['status'] in statsarr):
                #统计结算过的数量
                i+=1
            if (betcg['status'] in statsarrp):
                #统计平手或无效的数量
                k+=1
        if ( cg_count == i and cg_count > k ):
            #判断串关中的子项是否都已结算,且所有的子项不全是平手或无效
            if not (betcg_group['status'] == 1):
    
                mysql="update k_bet_cg_group set status=1 , update_time=now() , is_jiesuan=1 ,  fs=0  where gid="+str(row['gid'])
                cur.execute(mysql)  
                try:
                    #注单中奖，给会员账户增加奖金               
                    win_money =betcg_group['win']
                    mysql="update k_user set money=money+"+str(win_money)+"  where uid="+str(row['uid'])
                    cur.execute(mysql)
                    cur.execute("select * from k_user where uid="+str(row['uid']))
                    users = cur.fetchall()                    
                    user =users[0] 
                    sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+user['site_id']+"','"+str(betcg_group['gid'])+"','"+str(user['username'])+"','"+str(betcg_group['uid'])+"','"+str(betcg_group['agent_id'])+"',15,1,'"+str(win_money)+"',"+str(user['money'])+unicode(",now(),'自动结算串關id：",'utf-8')+str(betcg_group['number'])+unicode(" 類型:串關',0,'"+str(user['index_id'])+"') ",'utf-8')
                    cur.execute(sql) 
                except MySQLdb.Error, e:
                    con.rollback()
                    print "Error %d: %s" % (e.args[0],e.args[1])
                    print "会员修改失败!!!"+str(row['id'])                
        elif (cg_count == k):
            #如果所有子项都是平手或无效,则把串关状态设为3,并把串关的已赢金额设为下注金额.
            if not (betcg_group['status'] == 3):
                mysql="update k_bet_cg_group set status=3 , update_time=now() , is_jiesuan=1 , win=bet_money  where gid="+str(row['gid'])
                cur.execute(mysql)  
                try:
                    win_money =betcg_group['bet_money']
                    mysql="update k_user set money=money+"+str(win_money)+"  where uid="+str(row['uid'])
                    cur.execute(mysql)
                    cur.execute("select * from k_user where uid="+str(row['uid']))
                    users = cur.fetchall()                    
                    user =users[0]                   
                    sql = " insert into k_user_cash_record (site_id ,source_id,username,uid,agent_id,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num,index_id) values ('"+user['site_id']+"','"+str(betcg_group['gid'])+"','"+str(user['username'])+"','"+str(betcg_group['uid'])+"','"+str(betcg_group['agent_id'])+"',20,1,'"+str(win_money)+"',"+str(user['money'])+unicode(",now(),'自动结算串關id：",'utf-8')+str(betcg_group['gid'])+unicode(" 類型:串關',0,'"+str(user['index_id'])+"') ",'utf-8')
                    cur.execute(sql)
                except MySQLdb.Error, e:
                    con.rollback()
                    print "Error %d: %s" % (e.args[0],e.args[1])
                    print "会员修改失败!!!"+str(row['id'])   
        con.commit()
        cur.close()     
        con.close() 
    
#结算串关单式

def cg_bet_ds(t,row):
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8')
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    #获取对应的组数据
    cur.execute("select * from k_bet_cg_group where gid="+str(row['gid']))
    betcg_group = cur.fetchall()
    betcg_group = betcg_group[0]
    win = 0
    if (t['status']==1):
            win = betcg_group['win']
            bet_money = betcg_group['bet_money']
            point = row['ben_add'] + row['bet_point']
            if (win == 0):
                #如果该组第一次结算
                win = bet_money * point
            else:
                #第二次结算
                win = win * point
    elif (t['status']==2):
            win = 0
    elif(t['status']==3):
            win = 0
    elif(t['status']==4):
            win = betcg_group['win']
            bet_money = betcg_group['bet_money']
            if(row['ben_add'] ==1):
                point = 1 + float(row['bet_point']) / 2
            else:
                point = float(row['bet_point']) / 2
            point = Decimal(str(point))
            if (win == 0):
                #如果该组第一次结算
                win = bet_money * point
            else:
                #第二次结算
                win = win * point
    elif(t['status']==5):
            win = betcg_group['win'];
            bet_money = betcg_group['bet_money'];
            point = Decimal(str(0.5))
            if (win == 0):
                #如果该组第一次结算
                win = bet_money * point
            else:
                #第二次结算
                win = win * point
    elif(t['status']==8):
            win = 0
    else:
        win = 0

    #更新单式   
    mysql="update k_bet_cg set status="+str(t['status'])+" , mb_inball='"+str(t['mb_inball'])+"'  , tg_inball='"+str(t['tg_inball'])+"', update_time=now() where status =0 and bid="+str(row['bid'])
    cur.execute(mysql)
    
    #更新串式
    if not (t['status'] == 8 or betcg_group['status']==2):
        if not (t['status'] == 3):
            set =' win='+str(win)
        else:
            set =' cg_count=cg_count-1 ' 
        if (t['status'] == 2):
            set = set +' , status=2 ,update_time=now() , is_jiesuan=1 '          
        mysql="update k_bet_cg_group set "+set+" where gid="+str(row['gid'])
        print mysql
        cur.execute(mysql)
    con.commit()
    cur.close()     
    con.close()        
# 体彩判断输赢
# @param  [type] column         [description]
# @param  [type] mb_inball      [description]  全场 主场的进球数
# @param  [type] tg_inball      [description]  全场 客场的进球数
# @param  [type] mb_inball_hr   [description]  上半场 主场的进球数
# @param  [type] tg_inball_hr   [description]  上半场 客场的进球数
# @param  [type] match_type     [description]  row["match_type"] 下注球赛类型  2滚球
# @param  [type] match_showtype [description]  row["match_showtype"]  让球类型 h主场让球,c客场让球
# @param  [type] rgg            [description]  row["match_rgg"] 让球数
# @param  [type] dxgg           [description]  row["match_dxgg"]  大小盘口
# @param  [type] match_nowscore [description]  row["match_nowscore"] 当前比分
# @return [Array]  status 输赢的状态 1为赢,2输,3无效,4赢一半,5输一半,6其他无效 进球无效,7红卡取消,8和局

   
def make_point(column, mb_inball, tg_inball, mb_inball_hr, tg_inball_hr,
        match_type, match_showtype, rgg, dxgg, match_nowscore):
    print mb_inball
    print tg_inball
    print mb_inball_hr
    print tg_inball_hr
    if(mb_inball.isdigit() or mb_inball =='-1'):
        mb_inball=int(mb_inball)
    if(tg_inball.isdigit() or tg_inball =='-1'):
        tg_inball=int(tg_inball)
    if(mb_inball_hr.isdigit() or mb_inball_hr =='-1'):
        mb_inball_hr=int(mb_inball_hr)
    if(tg_inball_hr.isdigit() or tg_inball_hr =='-1'):
        tg_inball_hr=int(tg_inball_hr)    
    if (mb_inball < 0 ):
        #全场取消
        return {"column" : column, "ben_add" : 0, "status" : 3, "mb_inball" : str(mb_inball), "tg_inball" : str(tg_inball)}
    elif (mb_inball == "" and mb_inball_hr < 0):
        #半场取消
        return {"column" : column, "ben_add" : 0, "status" : 3, "mb_inball" : str(mb_inball_hr), "tg_inball" : str(tg_inball_hr)}
    elif (mb_inball == "" and mb_inball_hr == ""):
        return {"column" : column, "ben_add" : 0, "status" : 3, "mb_inball" : str(mb_inball_hr), "tg_inball" : str(tg_inball_hr)}
    ben_add = 0
    status = 2  #默认为输
    print column
    if(column =='match_bzm'):   #标准盘独赢 --标配的主场赢
        if (mb_inball > tg_inball):
            status = 1
    elif(column =='match_bzg'):    #--标配的客场赢
        if (mb_inball < tg_inball):
            status = 1
    elif(column =='match_bzh'):    ##--标配的和局
        if (mb_inball == tg_inball):
            status = 1
    elif(column =='match_ho'):    #主让球盘
        m = rgg.split('/')   #让球
        ben_add = 1
        temp =0
        if (len(m) == 2):
            for k in m :
                k = string.atof(k)
                if (match_showtype.lower() == 'h'):
                    #??
                    mb_temp = mb_inball
                    tg_temp = tg_inball + k
                else :
                    mb_temp = mb_inball + k
                    tg_temp = tg_inball
                if (match_type == 2 and not match_nowscore=='unneed'):
                    # 如果是滚球,减去下注比分
                    n = match_nowscore.split(':')
                    n[0] = string.atof(n[0])
                    n[1] = string.atof(n[1])
                    mb_temp -= n[0]
                    tg_temp -= n[1]
                if (mb_temp > tg_temp):
                    temp += 1
                elif (mb_temp == tg_temp):
                    temp += 0.5
                else :
                    temp += 0
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            rgg = string.atof(rgg)
            if (match_showtype.lower() == 'h'):
                mb_temp = mb_inball
                tg_temp = tg_inball + rgg
            else:
                mb_temp = mb_inball + rgg
                tg_temp = tg_inball
            if (match_type == 2 and not match_nowscore=='unneed'):
                # 如果是滚球,减去下注比分
                n = match_nowscore.split(':')
                n[0] = string.atof(n[0])
                n[1] = string.atof(n[1])
                mb_temp -= n[0]
                tg_temp -= n[1]
            if (mb_temp > tg_temp):
                status = 1
            elif (mb_temp == tg_temp):
                status = 8
            else:
                status = 2
    elif(column =='match_ao'):    #让球盘
        m = rgg.split('/')
        ben_add = 1
        temp =0
        if (len(m) == 2):
            for k in m : 
                k = string.atof(k)
                if (match_showtype.lower() == 'h'):
                    mb_temp = mb_inball
                    tg_temp = tg_inball + k
                else:
                    mb_temp = mb_inball + k
                    tg_temp = tg_inball
                if (match_type == 2 and not match_nowscore=='unneed' ):
                    # 如果是滚球,减去下注比分
                    n =match_nowscore.split(':')
                    n[0] = string.atof(n[0])
                    n[1] = string.atof(n[1])
                    mb_temp -= n[0]
                    tg_temp -= n[1]
                if (mb_temp < tg_temp):
                    temp += 1
                elif (mb_temp == tg_temp):
                    temp += 0.5
                else:
                    temp += 0
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            rgg = string.atof(rgg)
            if (match_showtype.lower() == 'h'):
                mb_temp = mb_inball
                tg_temp = tg_inball + rgg
            else:
                mb_temp = mb_inball + rgg
                tg_temp = tg_inball
            if (match_type == 2 and not match_nowscore=='unneed'):
                # 如果是滚球,减去下注比分
                n = match_nowscore.split(':')
                n[0] = string.atof(n[0])
                n[1] = string.atof(n[1])
                mb_temp -= n[0]
                tg_temp -= n[1]
            if (mb_temp < tg_temp):
                status = 1
            elif (mb_temp == tg_temp):
                status = 8
            else:
                status = 2
#---------------------------大小,单双竞彩
    elif (column == 'match_dxdpl'):    #大小盘
        m = dxgg.split('/')
        ben_add = 1
        total = mb_inball + tg_inball
        temp =0
        if (len(m) == 2):
            for t in m:
                t = string.atof(t)
                if (total > t):
                    temp += 1
                elif (total == t):
                    temp += 0.5
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            dxgg = string.atof(dxgg)
            if (total > dxgg):
                status = 1
            elif (total == dxgg):
                status = 8
            else:
                status = 2
    elif (column =='match_dxxpl'):    #大小盘
        m = dxgg.split('/')
        ben_add = 1
        total = mb_inball + tg_inball
        print mb_inball
        print tg_inball
        temp =0
        if (len(m) == 2):
            for t in m :
                t = string.atof(t)
                if (total < t):
                    temp += 1
                elif (total == t):
                    temp += 0.5
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            dxgg = string.atof(dxgg)
            if (total < dxgg):
                status = 1
            elif (total == dxgg):
                status = 8
            else:
                status = 2
    elif (column =='match_dsdpl'):
        if ((mb_inball + tg_inball) % 2 == 1):
            status = 1;
    elif (column == 'match_dsspl'):
        if ((mb_inball + tg_inball) % 2 == 0):
            status = 1
#--------------------------------------主场赢
    elif (column == 'match_bmdy'):    #上半场独赢
        if (mb_inball_hr > tg_inball_hr):
            status = 1
        mb_inball = mb_inball_hr
        tg_inball = tg_inball_hr
    elif (column == 'match_bgdy'):
        if (mb_inball_hr < tg_inball_hr):
            status = 1
        mb_inball = mb_inball_hr
        tg_inball = tg_inball_hr
    elif (column =='match_bhdy'):
        if (mb_inball_hr == tg_inball_hr):
            status = 1
        mb_inball = mb_inball_hr
        tg_inball = tg_inball_hr
#-------------------------------------
    elif (column =='match_bho'):
        m = rgg.split('/')
        ben_add = 1
        temp =0
        if (len(m) == 2):
            for k in m :
                k = string.atof(k)
                if (match_showtype.lower() == 'h'):
                    mb_temp = mb_inball_hr
                    tg_temp = tg_inball_hr + k
                else:
                    mb_temp = mb_inball_hr + k
                    tg_temp = tg_inball_hr
                if (match_type == 2 and not match_nowscore=='unneed' ):
                    # 如果是滚球,减去下注比分
                    n = match_nowscore.split(':')
                    n[0] = string.atof(n[0])
                    n[1] = string.atof(n[1])
                    mb_temp -= n[0]
                    tg_temp -= n[1]
                if (mb_temp > tg_temp):
                    temp += 1
                elif (mb_temp == tg_temp):
                    temp += 0.5
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            rgg = string.atof(rgg)
            if (match_showtype.lower() == 'h'):
                mb_temp = mb_inball_hr;
                tg_temp = tg_inball_hr + rgg
            else:
                mb_temp = mb_inball_hr + rgg
                tg_temp = tg_inball_hr
            if (match_type == 2 and not match_nowscore=='unneed'):
                # 如果是滚球,减去下注比分
                n = match_nowscore.split(':')
                n[0] = string.atof(n[0])
                n[1] = string.atof(n[1])
                mb_temp -= n[0]
                tg_temp -= n[1]
            if (mb_temp > tg_temp):
                status = 1
            elif (mb_temp == tg_temp):
                status = 8
            else:
                status = 2
        mb_inball = mb_inball_hr
        tg_inball = tg_inball_hr
    elif (column == 'match_bao'):
        m = rgg.split('/')
        ben_add = 1;
        temp =0
        if (len(m) == 2):
            for k in m :
                k = string.atof(k)
                if (match_showtype.lower() == 'h'):
                    mb_temp = mb_inball_hr
                    tg_temp = tg_inball_hr + k
                else:
                    mb_temp = mb_inball_hr + k
                    tg_temp = tg_inball_hr
                if (match_type == 2 and not match_nowscore=='unneed' ):
                    # 如果是滚球,减去下注比分
                    n =  match_nowscore.split(':')
                    n[0] = string.atof(n[0])
                    n[1] = string.atof(n[1])
                    mb_temp -= int(n[0])
                    tg_temp -= int(n[1])
                if (mb_temp < tg_temp):
                    temp += 1
                elif (mb_temp == tg_temp):
                    temp += 0.5
                else:
                    temp += 0
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            rgg = string.atof(rgg)
            if (match_showtype.lower() == 'h'):
                mb_temp = mb_inball_hr
                tg_temp = tg_inball_hr + rgg
            else:
                mb_temp = mb_inball_hr + rgg
                tg_temp = tg_inball_hr
            if (match_type == 2 and not match_nowscore=='unneed'):
                # 如果是滚球,减去下注比分
                n = match_nowscore.split(':')
                n[0] = string.atof(n[0])
                n[1] = string.atof(n[1])
                mb_temp -= n[0]
                tg_temp -= n[1]
            if (mb_temp < tg_temp):
                status = 1
            elif (mb_temp == tg_temp):
                status = 8
            else:
                status = 2
        mb_inball = mb_inball_hr
        tg_inball = tg_inball_hr
#------------------------------------------------
    elif (column =='match_bdpl'):
        m = dxgg.split('/')
        ben_add = 1
        temp =0
        total = mb_inball_hr + tg_inball_hr
        if (len(m) == 2):
            for t in m :
                t = string.atof(t)
                if (total > t):
                    temp += 1
                elif (total == t):
                    temp += 0.5
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            dxgg = string.atof(dxgg)
            if (total > dxgg):
                status = 1
            elif (total == dxgg):
                status = 8
            else:
                status = 2
        mb_inball = mb_inball_hr
        tg_inball = tg_inball_hr
    elif (column == 'match_bxpl'):
        m = dxgg.split('/')
        ben_add = 1
        total = mb_inball_hr + tg_inball_hr
        temp =0
        if (len(m) == 2):
            for t in m :
                t = string.atof(t)
                if (total < t):
                    temp += 1
                elif (total == t):
                    temp += 0.5
                else:
                    temp += 0
            if (temp == 0.5):
                status = 5
            elif (temp == 1.5):
                status = 4
            elif (temp == 2):
                status = 1
            elif (temp == 0):
                status = 2
        else:
            dxgg = string.atof(dxgg)
            if (total < dxgg):
                status = 1
            elif (total == dxgg):
                status = 8
            else:
                status = 2
        mb_inball = mb_inball_hr
        tg_inball = tg_inball_hr
#---------------------以下是波胆盘 波胆竞猜
    elif (column == 'match_bd10'):    #波胆
        if ((mb_inball == 1) and (tg_inball == 0)):
            status = 1
    elif (column == 'match_bd20'):    #波胆
        if ((mb_inball == 2) and (tg_inball == 0)):
            status = 1
    elif (column == 'match_bd21'):    #波胆
        if ((mb_inball == 2) and (tg_inball == 1)):
            status = 1
    elif (column == 'match_bd30'):    #波胆
        if ((mb_inball == 3) and (tg_inball == 0)):
            status = 1
    elif (column == 'match_bd31'):    #波胆
        if ((mb_inball == 3) and (tg_inball == 1)):
            status = 1
    elif (column == 'match_bd32'):    #波胆
        if ((mb_inball == 3) and (tg_inball == 2)):
            status = 1
    elif (column =='match_bd40'):    #波胆
        if ((mb_inball == 4) and (tg_inball == 0)):
            status = 1
    elif (column =='match_bd41'):    #波胆
        if ((mb_inball == 4) and (tg_inball == 1)):
            status = 1
    elif (column =='match_bd42'):    #波胆
        if ((mb_inball == 4) and (tg_inball == 2)):
            status = 1
    elif (column =='match_bd43'):    #波胆
        if ((mb_inball == 4) and (tg_inball == 3)):
            status = 1
    elif (column =='match_bd00'):    #波胆
        if ((mb_inball == 0) and (tg_inball == 0)):
            status = 1
    elif (column == 'match_bd11'):    #波胆
        if ((mb_inball == 1) and (tg_inball == 1)):
            status = 1
    elif (column == 'match_bd22'):    #波胆
        if ((mb_inball == 2) and (tg_inball == 2)):
            status = 1
    elif (column == 'match_bd33'):    #波胆
        if ((mb_inball == 3) and (tg_inball == 3)):
            status = 1
    elif (column == 'match_bd44'):    #波胆
        if ((mb_inball == 4) and (tg_inball == 4)):
            status = 1
    elif (column == 'match_bdup5'):
        if ((mb_inball >= 5) or (tg_inball >= 5)):
            status = 1
    elif (column == 'match_bdg10'):
        if ((mb_inball == 0) and (tg_inball == 1)):
            status = 1
    elif (column == 'match_bdg20'):
        if ((mb_inball == 0) and (tg_inball == 2)):
            status = 1
    elif (column == 'match_bdg21'):
        if ((mb_inball == 1) and (tg_inball == 2)):
            status = 1
    elif (column == 'match_bdg30'):
        if ((mb_inball == 0) and (tg_inball == 3)):
            status = 1
    elif (column == 'match_bdg31'):
        if ((mb_inball == 1) and (tg_inball == 3)):
            status = 1
    elif (column == 'match_bdg32'):
        if ((mb_inball == 2) and (tg_inball == 3)):
            status = 1
    elif (column == 'match_bdg40'):
        if ((mb_inball == 0) and (tg_inball == 4)):
            status = 1
    elif (column == 'match_bdg41'):
        if ((mb_inball == 1) and (tg_inball == 4)):
            status = 1
    elif (column == 'match_bdg42'):
        if ((mb_inball == 2) and (tg_inball == 4)):
            status = 1
    elif (column == 'match_bdg43'):
        if ((mb_inball == 3) and (tg_inball == 4)):
            status = 1
    elif (column == 'match_hr_bd10'):
        if ((mb_inball_hr == 1) and (tg_inball_hr == 0)):
            status = 1
    elif (column == 'match_hr_bd20'):
        if ((mb_inball_hr == 2) and (tg_inball_hr == 0)):
            status = 1
    elif (column == 'match_hr_bd21'):
        if ((mb_inball_hr == 2) and (tg_inball_hr == 1)):
            status = 1
    elif (column == 'match_hr_bd30'):
        if ((mb_inball_hr == 3) and (tg_inball_hr == 0)):
            status = 1
    elif (column == 'match_hr_bd31'):
        if ((mb_inball_hr == 3) and (tg_inball_hr == 1)):
            status = 1
    elif (column == 'match_hr_bd32'):
        if ((mb_inball_hr == 3) and (tg_inball_hr == 2)):
            status = 1
    elif (column == 'match_hr_bd40'):
        if ((mb_inball_hr == 4) and (tg_inball_hr == 0)):
            status = 1
    elif (column == 'match_hr_bd41'):
        if ((mb_inball_hr == 4) and (tg_inball_hr == 1)):
            status = 1
    elif (column == 'match_hr_bd42'):
        if ((mb_inball_hr == 4) and (tg_inball_hr == 2)):
            status = 1
    elif (column == 'match_hr_bd43'):
        if ((mb_inball_hr == 4) and (tg_inball_hr == 3)):
            status = 1
    elif (column == 'match_hr_bd00'):
        if ((mb_inball_hr == 0) and (tg_inball_hr == 0)):
            status = 1
    elif (column == 'match_hr_bd11'):
        if ((mb_inball_hr == 1) and (tg_inball_hr == 1)):
            status = 1
    elif (column == 'match_hr_bd22'):
        if ((mb_inball_hr == 2) and (tg_inball_hr == 2)):
            status = 1
    elif (column == 'match_hr_bd33'):
        if ((mb_inball_hr == 3) and (tg_inball_hr == 3)):
            status = 1
    elif (column == 'match_hr_bd44'):
        if ((mb_inball_hr == 4) and (tg_inball_hr == 4)):
            status = 1
    elif (column == 'match_hr_bdup5'):
        if ((mb_inball_hr >= 5) or (tg_inball_hr >= 5)):
            status = 1
    elif (column == 'match_hr_bdg10'):
        if ((mb_inball_hr == 0) and (tg_inball_hr == 1)):
            status = 1
    elif (column == 'match_hr_bdg20'):
        if ((mb_inball_hr == 0) and (tg_inball_hr == 2)):
            status = 1
    elif (column == 'match_hr_bdg21'):
        if ((mb_inball_hr == 1) and (tg_inball_hr == 2)):
            status = 1
    elif (column == 'match_hr_bdg30'):
        if ((mb_inball_hr == 0) and (tg_inball_hr == 3)):
            status = 1
    elif (column == 'match_hr_bdg31'):
        if ((mb_inball_hr == 1) and (tg_inball_hr == 3)):
            status = 1
    elif (column == 'match_hr_bdg32'):
        if ((mb_inball_hr == 2) and (tg_inball_hr == 3)):
            status = 1
    elif (column == 'match_hr_bdg40'):
        if ((mb_inball_hr == 0) and (tg_inball_hr == 4)):
            status = 1
    elif (column == 'match_hr_bdg41'):
        if ((mb_inball_hr == 1) and (tg_inball_hr == 4)):
            status = 1
    elif (column == 'match_hr_bdg42'):
        if ((mb_inball_hr == 2) and (tg_inball_hr == 4)):
            status = 1
    elif (column == 'match_hr_bdg43'):
        if ((mb_inball_hr == 3) and (tg_inball_hr == 4)):
            status = 1
#------------------入球数竞猜
    elif (column == 'match_total01pl'):
        total = mb_inball + tg_inball
        if ((total >= 0) and (total <= 1)):
            status = 1
    elif (column == 'match_total23pl'):
        total = mb_inball + tg_inball
        if ((total >= 2) and (total <= 3)):
            status = 1
    elif (column == 'match_total46pl'):
        total = mb_inball + tg_inball
        if ((total >= 4) and (total <= 6)):
            status = 1
    elif (column == 'match_total7uppl'):
        total = mb_inball + tg_inball
        if ((total >= 7)):
            status = 1
#------------------半全场竞猜和派彩
    elif (column == 'match_bqmm'):    #主/主
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] > tg_inball[1]) and (mb_inball[0] > tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    elif (column == 'match_bqmh'):    #主/和
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] == tg_inball[1]) and (mb_inball[0] > tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])  
    elif (column == 'match_bqmg'):    #主/客
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] < tg_inball[1]) and (mb_inball[0] > tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    elif (column == 'match_bqhm'):    #和/主
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] > tg_inball[1]) and (mb_inball[0] == tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    elif (column == 'match_bqhh'):    #和/和
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] == tg_inball[1]) and (mb_inball[0] == tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    elif (column == 'match_bqhg'):    #和/客
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] < tg_inball[1]) and (mb_inball[0] == tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    elif (column == 'match_bqgm'):    #客/主
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] > tg_inball[1]) and (mb_inball[0] < tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    elif (column == 'match_bqgh'):    #客/和
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] == tg_inball[1]) and (mb_inball[0] < tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    elif (column == 'match_bqgg'):    #客/客
        mb_inball = mb_inball.split('/')
        tg_inball = tg_inball.split('/')
        if ((mb_inball[1] < tg_inball[1]) and (mb_inball[0] < tg_inball[0])):
            status = 1
        mb_inball = int(mb_inball[1])
        tg_inball = int(tg_inball[1])
    date = {"column" : column, "ben_add": ben_add, "status" : status, "mb_inball" : '%d'%mb_inball, "tg_inball" : '%d'%tg_inball}
    return date
#end of model