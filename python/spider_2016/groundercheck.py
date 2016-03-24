# -*- coding: utf-8 -*-
#encoding=utf-8

import datetime
import time
#import threading
#import Queue
#import sys
import urllib2
#import urllib
#import cookielib
import re
import MySQLdb
import MySQLdb as mdb
import chardet
#import string
import redis
import json
import math
from login_ini import getUid
import globalConfig
HOST = globalConfig.HOST
RedisClent = redis.Redis(host=HOST, port=6379)
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_USER_P = globalConfig.DB_USER_P
DB_PASSWD_P = globalConfig.DB_PASSWD_P
DB_NAME = globalConfig.DB_NAME
DB_NAME_P = globalConfig.DB_NAME_P  


headers = {"User-Agent": "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5",
             "Referer": globalConfig.dominAll}
def get_HK_ior(H_ratio,C_ratio):
    #print str(H_ratio)+"-"+str(C_ratio)
    H_ratio=H_ratio*1000
    C_ratio=C_ratio*1000
    out_ior=[0,1]
    nowType=""
    if H_ratio <= 1000 and C_ratio <= 1000:
            out_ior[0]=H_ratio
            out_ior[1]=C_ratio
    else:
        line=2000 - ( H_ratio + C_ratio )
        if (H_ratio > C_ratio):
            lowRatio=C_ratio
            nowType = "C"
        else:
            lowRatio = H_ratio
            nowType = "H"
        if (((2000 - line) - lowRatio) > 1000):
            nowRatio = (lowRatio + line) * (-1)
        else:
            nowRatio=(2000 - line) - lowRatio
        if (nowRatio < 0):
            #highRatio = floor(abs(1000 / nowRatio) * 1000)
            highRatio = math.floor(abs(1000 / nowRatio) * 1000)
        else:
            highRatio = (2000 - line - nowRatio)

        if (nowType == "H"):
            out_ior[0]=lowRatio
            out_ior[1]=highRatio
        else:
            out_ior[0]=highRatio
            out_ior[1]=lowRatio

    out_ior[0]=round(out_ior[0]/1000,2)
    out_ior[1]=round(out_ior[1]/1000,2)
    return out_ior
def getDomian(uid):
    print globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid
    req = urllib2.Request(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=10)
    html = response.read()
    #response = urllib2.urlopen(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,timeout=10)
    #html = response.read()
    #print html
    url = re.compile(r'action=\'(.*?)\'').findall(html)
    if(url):
        return url[0]
    else:
        return globalConfig.dominAll

def getTitle(domain,url,uid,time=10):
    req = urllib2.Request(domain+"/app/member/mem_online.php?uid="+uid,None,headers)
    urllib2.urlopen(req,None,timeout=time)
    req = urllib2.Request(domain+url+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=time) 
    html = response.read()
    #urllib2.urlopen(domain+"/app/member/mem_online.php?uid="+uid,timeout=time)
    print 'URL=>'+domain+url+uid
    #response = urllib2.urlopen(domain+url+uid,timeout=time)
    #html = response.read()
    #print html
    reg = r'g\((.*?)\)\;'
    response.close()
    title = re.compile(reg).findall(html)
    for i in range(0, len(title)):
        infoencode = chardet.detect(title[i]).get('encoding','utf-8')
        title[i] = title[i].decode(infoencode,'ignore').encode('utf-8')
        title[i] = title[i].strip('[')
        title[i] = title[i].strip(']')
        reg = r'\'(.*?)\''
        title[i] = re.compile(reg).findall(title[i])
    return title
def getdata():
    uid = getUid()
    domain = getDomian(uid)
    print domain
    data=getTitle(domain,'/app/member/FT_browse/body_var.php?rtype=re&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&uid=',uid,time=10)
    return data
def cancelBet(status,bid,uid,msg_title,msg_info,why=''):
    con_p=MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_P,charset='utf8' )
    cur_p = con_p.cursor(cursorclass = MySQLdb.cursors.DictCursor)
    InsertTime=time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time()))
    q=False
    try:
        sql="select a.bet_money,a.balance,a.number,b.money,b.username,b.site_id,b.agent_id from k_bet a,k_user b where b.uid = "+str(uid)+" and  a.bid = "+str(bid)+"   and a.lose_ok=0 limit 0,1"
        print sql
        cur_p.execute(sql)
        rows=cur_p.fetchall()
        if rows:
            print rows
        else:
            con_p.rollback()
            return False
        sql =   "update k_bet,k_user set k_bet.is_jiesuan=1,k_bet.lose_ok=1,k_bet.status="+str(status)+",k_user.money=k_user.money+k_bet.bet_money,k_bet.win=k_bet.bet_money,k_bet.update_time='"+str(InsertTime)+"',k_bet.match_endtime='"+str(InsertTime)+"',k_bet.sys_about='"+why+"' where k_user.uid=k_bet.uid and k_bet.bid='"+str(bid)+"' and k_bet.status=0 and k_bet.lose_ok=0"
        print sql
        cur_p.execute(sql)
        con_p.commit()

        agent_id =str(rows[0]['agent_id'])
        siteid   =str(rows[0]['site_id'])
        bet_money=str(rows[0]['bet_money'])
        balance  =str(rows[0]['money']+rows[0]['bet_money'])
        number   =str(rows[0]['number'])
        username =str(rows[0]['username'])

        #print 1111111111,rows
        sql_cash    =   "insert into k_user_cash_record(site_id,uid,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,source_id,username,agent_id) values('"+siteid+"','"+str(uid)+"','22','1','"+bet_money+"','"+balance+"','"+InsertTime+"','体育注单："+number+" "+why+"',"+bid+",'"+username+"','"+agent_id+"')"
        print 'CASH:',sql_cash
        cur_p.execute(sql_cash)
        con_p.commit()
        try:
            sql_msg="insert into k_user_msg (msg_from,uid,msg_title,msg_info,msg_time,site_id,`type`,level) values ('系统消息','"+str(uid)+"','"+msg_title+"','"+msg_info+"','"+InsertTime+"','"+siteid+"',1,2)"
            print sql_msg
            cur_p.execute(sql_msg)
            con_p.commit()
        except mdb.Error,e:
            print "Error %d: %s" % (e.args[0],e.args[1])
        q=True
    except mdb.Error,e:
        con_p.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
    if q:
        con_p.commit()
    return True
def setOK(bid):
    con_p=MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_P,charset='utf8' )
    cur_p = con_p.cursor(cursorclass = MySQLdb.cursors.DictCursor)
    try:
        sql = "update k_bet set lose_ok=1,match_endtime=now() where bid="+bid+" and lose_ok=0"
        print sql
        cur_p.execute(sql)
        q=True
    except mdb.Error,e:
        con_p.rollback()
        print "Error %d: %s" % (e.args[0],e.args[1])
        q=False
    if q:
        con_p.commit()
    return q

def checkzhudan():
    try:
        con_p=MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_P,charset='utf8' )
        cur_p = con_p.cursor(cursorclass = MySQLdb.cursors.DictCursor)
        con=MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
        cur = con.cursor(cursorclass = MySQLdb.cursors.DictCursor)
    #if __name__ == '__main__':
        print 'REDIS START';
        ZQGQ=RedisClent.get('ZQGQ_JSON')
        d=json.loads(ZQGQ)
        print d
        lasttime=int(d[0][0])
        nowtime=time.time()
        #print nowtime
        timeout=int(nowtime-lasttime)
        print 'TimeOut:'+str(timeout),'NowTime:'+str(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(nowtime))),'LastTime:'+str(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(lasttime))),lasttime

        if timeout>10:
            d[1]=getdata()
            json_data = '[['+str(time.time())+'],'+json.dumps(d[1])+']'
            RedisClent.set('ZQGQ_JSON',json_data)
        Cache=d[1]
        zqgq=[]
        if Cache:
            k=0
            zqgq={}
            for r in Cache:
                if r[25] =='':r[25] ="0"
                if r[27] =='':r[27] ="0"
                if r[24] =='':r[24] ="0"
                if r[26] =='':r[26] ='0'
                if r[28] =='':r[28] ='0'
                if r[29] =='':r[29] ='0'
                if r[30] =='':r[30] ='0'
                if r[34] =='':r[34] ='0'
                if r[15] =='':r[15] ='0'
                if r[16] =='':r[16] ='0'
                if r[13] =='':r[13] ='0'
                if r[14] =='':r[14] ='0'
                if r[17] =='':r[17] ='0'
                if r[31] =='':r[31] ='0'
                if r[32] =='':r[32] ='0'
                if r[33] =='':r[33] ='0'
                if r[36] =='':r[36] ='0'
                if r[37] =='':r[37] ='0'
                if r[38] =='':r[38] ='0'
                if r[20] =='':r[20] ='0'
                if r[21] =='':r[21] ='0'
                if r[23] =='':r[23] ='0'
                if r[9] =='':r[9] ='0'
                if r[10] =='':r[10] ='0'
                if r[35] =='':r[35] ='0'
                r[11] = r[11].replace('O','')
                r[11] = ''.join(r[11].split())
                r[10] = ''.join(r[10].split())
                r[8] = ''.join(r[8].split())
                r[25] = ''.join(r[25].split())
                r[22] = ''.join(r[22].split())
                dx  =   []
                dx  =   get_HK_ior(float(r[9]),float(r[10]))
                r[9]    =   dx[0]
                r[10]   =   dx[1]
                dx  =   []
                dx  =   get_HK_ior(float(r[13]),float(r[14]))
                r[13]   =   dx[0]
                r[14]   =   dx[1]
                zqgq[k]={}
                zqgq[k]['Match_ID']=r[0]
                zqgq[k]['Match_Master']=r[5]
                zqgq[k]['Match_Guest']=r[6]
                zqgq[k]['Match_Name']=r[2]
                zqgq[k]['Match_Time']=r[1]
                zqgq[k]['Match_Ho']=r[9]
                zqgq[k]['Match_DxDpl']=r[14]
                zqgq[k]['Match_BHo']=r[23]
                zqgq[k]['Match_Bdpl']=r[28]
                zqgq[k]['Match_Ao']=r[10]
                zqgq[k]['Match_DxXpl']=r[13]
                zqgq[k]['Match_BAo']=r[24]
                zqgq[k]['Match_Bxpl']=r[27]
                zqgq[k]['Match_RGG']=r[8]
                zqgq[k]['Match_BRpk']=r[22]
                zqgq[k]['Match_ShowType']=r[7]
                zqgq[k]['Match_Hr_ShowType']=r[7]
                zqgq[k]['Match_DxGG']=r[11]
                zqgq[k]['Match_Bdxpk']=r[25]
                zqgq[k]['Match_HRedCard']=r[29]
                zqgq[k]['Match_GRedCard']=r[30]
                zqgq[k]['Match_NowScore']=r[18]+":"+r[19]
                zqgq[k]['Match_BzM']=r[33]
                zqgq[k]['Match_BzG']=r[34]
                zqgq[k]['Match_BzH']=r[35]
                zqgq[k]['Match_Bmdy']=r[36]
                zqgq[k]['Match_Bgdy']=r[37]
                zqgq[k]['Match_Bhdy']=r[38]
                zqgq[k]['Match_CoverDate']=lasttime
                zqgq[k]['Match_Date']=''
                zqgq[k]['Match_Type']=2
                k+=1
        print '足球滚球：',len(zqgq)

        #cur=con.cursor()
        sql="select match_nowscore,match_id,bid,uid,master_guest,bet_info,Match_GRedCard,Match_HRedCard,bet_money from k_bet where lose_ok=0 and bet_time<=DATE_SUB(now(),INTERVAL 60 SECOND) and bet_time>=DATE_SUB(now(),INTERVAL 180 SECOND)  order by bid  desc"
        #sql    = "select match_nowscore,match_id,bid,uid,master_guest,bet_info,Match_GRedCard,Match_HRedCard,bet_money from k_bet where lose_ok=0 and bet_time<='2015-08-27 05:41:15' and bet_time>='2015-04-27 03:41:15'  order by bid  desc ";
        count=cur_p.execute(sql)
        print 'there has %s rows record' % count
        d=cur_p.fetchall()

        #cur_p.commit()

        print d
        bet={}
        arr={}
        match_id=[]
        num=0
        n=0
        for v in d:
            bet[v['bid']]={}
            bet[v['bid']]['match_nowscore']=v['match_nowscore']
            bet[v['bid']]['match_id']=v['match_id']
            bet[v['bid']]['uid']=v['uid']
            bet[v['bid']]['master_guest']=v['master_guest']
            bet[v['bid']]['bet_info']=v['bet_info']
            bet[v['bid']]['Match_GRedCard']=v['Match_GRedCard']
            bet[v['bid']]['Match_HRedCard']=v['Match_HRedCard']
            bet[v['bid']]['bet_money']=v['bet_money']
            bet[v['bid']]['master_guest']=v['master_guest']
            bool=True #MATCH END
            for i in  range(len(zqgq)):
                if zqgq[i]['Match_ID']==v['match_id']:
                    arr[num]={}
                    arr[num]['i']=i
                    arr[num]['bid']=v['bid']
                    bool=False #MATCH
                    num+=1
                    break
                n+=1
            if bool:
                match_id.append(v['match_id'])
        match_id=','.join(set(match_id))
        print 'COO:',bet
        print '已结束赛事：'+match_id
        print '未结束赛事：'+str(num)
        print '缓存数据：',zqgq
        match_nowscore=''
        msg_=''
        #未结束的赛事从缓存比对
        for i in range(num):
            print '未结束赛事判断：'
            print num,'->',i
            print arr[i]['i']
            print zqgq[arr[i]['i']]['Match_NowScore']
            print i,arr[i]['bid'],bet[arr[i]['bid']]["match_nowscore"] , arr[i]['i'],zqgq[arr[i]['i']]['Match_NowScore']
            if bet[arr[i]['bid']]["match_nowscore"]==zqgq[arr[i]['i']]['Match_NowScore']:#比分未改变
                print 'RedCard:',bet[arr[i]['bid']]["Match_HRedCard"],zqgq[arr[i]['i']]['Match_HRedCard'],bet[arr[i]['bid']]["Match_GRedCard"],zqgq[arr[i]['i']]['Match_GRedCard']
                if bet[arr[i]['bid']]["Match_HRedCard"]!=int(zqgq[arr[i]['i']]['Match_HRedCard']) or bet[arr[i]['bid']]["Match_GRedCard"]!=int(zqgq[arr[i]['i']]['Match_GRedCard']): #主队或客队红牌，红牌无效
                    msg='滚球注单红卡无效'
                    cancelBet(str(7),str(arr[i]['bid']),bet[arr[i]['bid']]['uid'],bet[arr[i]['bid']]["master_guest"]+"_注单已取消",bet[arr[i]['bid']]["master_guest"]+'<br/>'+bet[arr[i]['bid']]["bet_info"]+'<br /><font style="color:#F00"/>因红卡无效，该注单取消,已返还本金。</font>','红卡无效')
                else:
                    msg='滚球注单有效'
                    setOK(str(arr[i]['bid']))
            else:#比分改变
                    msg='滚球注单进球无效'
                    cancelBet(str(6),str(arr[i]['bid']),bet[arr[i]['bid']]['uid'],bet[arr[i]['bid']]["master_guest"]+"_注单已取消",bet[arr[i]['bid']]["master_guest"]+'<br/>'+bet[arr[i]['bid']]["bet_info"]+'<br /><font style="color:#F00"/>因进球无效，该注单取消,已返还本金。</font>','进球无效')
            msg_+='审核注单编号 '+str(arr[i]['bid'])+' 为  '+msg+'\r\n'





        #已结束赛事从数据库比对
        print bet
        if match_id:
            sql ="select Match_HRedCard,Match_GRedCard,Match_NowScore,Match_ID,Match_LstTime from bet_match where Match_Type=2 and Match_ID in("+match_id+")"
            print(sql)
            count=cur.execute(sql)
            cur.commit()
            print 'there has %s rows record' % count
            d=cur.fetchall()
            for rows in d:
                for key,v in bet.items():
                    #print 'BET:',bet[i]
                    #print bet[i]["match_id"],rows["Match_ID"]
                     if v["match_id"] == rows["Match_ID"]:#有用户下注了这场赛事
                            print v["match_nowscore"],rows['Match_NowScore']
                            if v["match_nowscore"]==rows['Match_NowScore']:#比分未改变
                                print 'RedCard:',v["Match_HRedCard"],rows['Match_HRedCard'],'-',v["Match_GRedCard"],rows['Match_GRedCard']
                                if v["Match_HRedCard"]!=rows['Match_HRedCard'] or v["Match_GRedCard"]!=rows['Match_GRedCard']: #主队或客队红牌，红牌无效
                                    msg='Match_End 滚球注单红卡无效'
                                    cancelBet(str(7),str(key),v['uid'],v["master_guest"]+"_注单已取消",v["master_guest"]+'<br/>'+v["bet_info"]+'<br /><font style="color:#F00"/>因红卡无效，该注单取消,已返还本金。</font>','红卡无效')
                                else:
                                    msg='Match_End 滚球注单有效'
                                    setOK(str(key))
                            else:#比分改变
                                    msg='Match_End 滚球注单进球无效'
                                    cancelBet(str(6),str(key),v['uid'],str(v["master_guest"])+"_注单已取消",str(v["master_guest"])+'<br/>'+str(v["bet_info"])+'<br /><font style="color:#F00"/>因进球无效，该注单取消,已返还本金。</font>','进球无效')

                            msg_+='审核注单编号 '+str(key)+' 为  '+msg+'\r\n'
        print msg_
        cur.close()
        con.close()
        cur_p.close()
        con_p.close()
    except:
        print 'Error Check'

def runTask(func, day=0, hour=0, min=0, second=0):
    func()
    nowtime = datetime.datetime.now()
    strnow = nowtime.strftime('%Y-%m-%d %H:%M:%S')
    print "now:",strnow
    period = datetime.timedelta(days=day, hours=hour, minutes=min, seconds=second)
    next_time = nowtime + period
    strnext_time = next_time.strftime('%Y-%m-%d %H:%M:%S')
    print "next run:",strnext_time
    while True:
        iter_now = datetime.datetime.now()
        iter_now_time = iter_now.strftime('%Y-%m-%d %H:%M:%S')
        if str(iter_now_time) >= str(strnext_time):
            print "start work: %s" % iter_now_time
            func()
            print "task done."
            iter_time = iter_now + period
            strnext_time = iter_time.strftime('%Y-%m-%d %H:%M:%S')
            print "next_iter: %s" % strnext_time
            continue
#run()
runTask(checkzhudan,0,0,0,25)

