# -*- coding: utf-8 -*-
#encoding=utf-8

import datetime
import time
import threading
import Queue
import sys
import urllib2
import urllib
import cookielib  
import re
import MySQLdb
import chardet
import string
import redis
import json
import math
from login_ini import getUid
import globalConfig
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_USER_P = globalConfig.DB_USER_P
DB_PASSWD_P = globalConfig.DB_PASSWD_P
DB_NAME = globalConfig.DB_NAME
DB_NAME_P = globalConfig.DB_NAME_P  
#
HOST = globalConfig.HOST
RedisClent = redis.Redis(host=HOST, port=6379)
#client =  redis.StrictRedis(host='192.168.1.9', port=6379)
class RedisDBConfig:
    HOST = HOST
    PORT = 6379
    DBID = 0
#
#


THREAD_LIMIT = 2
jobs = Queue.Queue(5)
singlelock = threading.Lock()
info = Queue.Queue()
  
headers = {"User-Agent": "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5",
             "Referer": globalConfig.dominAll} 

# import urllib
# 
# proto, rest = urllib.splittype(url)
# res, rest = urllib.splithost(rest)
# print "unkonw" if not res else res

def operator_status(func):
    '''''get operatoration status
    '''
    def gen_status(*args, **kwargs):
        error, result = None, None
        try:
            result = func(*args, **kwargs)
        except Exception as e:
            error = str(e)

        return {'result': result, 'error':  error}
    return gen_status

class RedisCache(object):
    def __init__(self):
        if not hasattr(RedisCache, 'pool'):
            RedisCache.create_pool()
        self._connection = redis.Redis(connection_pool = RedisCache.pool)

    @staticmethod
    def create_pool():
        RedisCache.pool = redis.ConnectionPool(
                host = RedisDBConfig.HOST,
                port = RedisDBConfig.PORT,
                db   = RedisDBConfig.DBID)

    @operator_status
    def set_data(self, key, value):
        '''''set data with (key, value)
        '''
        return self._connection.set(key, value)

    @operator_status
    def get_data(self, key):
        '''''get data by key
        '''
        return self._connection.get(key)

    @operator_status
    def del_data(self, key):
        '''''delete cache by key
        '''
        return self._connection.delete(key)
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

def getDomian():
    print globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid
    req = urllib2.Request(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=10) 
    #response = urllib2.urlopen(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,timeout=10)
    html = response.read()
    print html
    url = re.compile(r'action=\'(.*?)\'').findall(html) 
    if(url):
        return url[0]
    else:
        return globalConfig.dominAll

uid = getUid()
domain = getDomian()
print domain
i_headers = {"User-Agent": "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5",
             "Referer": domain+'/app/member/FT_future/index.php?rtype=pd&langx=zh-cn&mtype=4&showtype=future&hot_game=&league_id=&uid='+uid}
def workerbee(inputlist):
    for x in xrange(THREAD_LIMIT):
        print 'Thead {0} started.'.format(x)
        t = spider()
        t.start()
    for i in inputlist:
        try:
            jobs.put(i, block=True, timeout=5)
        except:
            singlelock.acquire()
            print "The queue is full !"
            singlelock.release()
  
    # Wait for the threads to finish
    singlelock.acquire()        # Acquire the lock so we can print
    print "Waiting for threads to finish."
    singlelock.release()        # Release the lock
    jobs.join()              # This command waits for all threads to finish.

def getPage(url,time=10):
    req = urllib2.Request(domain+"/app/member/mem_online.php?uid="+uid,None,i_headers)
    urllib2.urlopen(req,None,timeout=time)
    req = urllib2.Request(url+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=time) 
    html = response.read()
    reg = r'page\=(.*?)\;'
    response.close()
    page = re.compile(reg).findall(html)
    return page
     
    
def getTitle(Uid,url,uid,page,time=10):
    req = urllib2.Request(domain+"/app/member/mem_online.php?uid="+uid,None,i_headers)
    urllib2.urlopen(req,None,timeout=time) 
    j = 1
    old = url
    while (j <= page):
        _page = j-1
        url = old.replace("page_no=0", "page_no="+'%d' %_page)
        print url+uid
        req = urllib2.Request(url+uid,None,i_headers)
        response = urllib2.urlopen(req,None,timeout=time) 
        #response = urllib2.urlopen(url+uid,timeout=time)
        html = response.read()
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
        if Uid==13 or Uid==14 or Uid==17:
            if Uid==13:table='tennis_match'
            if Uid==14:table='volleyball_match'
            if Uid==17:table='baseball_match'
            operator.get(Uid)(title,table)
        else:
            operator.get(Uid)(title)
        j = j+1
    else: 
        title=0     
    return title


def GQbd27(title):  
    print 'REDIS START';
    json_data = '[['+str(time.time())+'],'+json.dumps(title)+']'
    RedisClent.set('ZQGQ_JSON',json_data)
    print 'REDIS END';
    print title
    for ti in title:
        if('测试' in ti[2]):
            continue
        status=0
        if('半场' in ti[1]):
            status=1
        if(('p' in ti[1]) or ('a' in ti[1])):
            ti[1] ='0'
        if('font' in ti[1]): 
            ti[1] ='45.5'
        js = 0
        timematch = string.atof(ti[1])
        if(timematch > 88 ):
            js = -1
        date = time.strftime('%m-%d',time.localtime(time.time()))
        M = time.strftime('%M',time.localtime(time.time()))
        h = time.strftime('%H',time.localtime(time.time()))
        ap = 'a' #默认为上午
        h = int(h)

        if(h>=12):
            if(h>12):
                h=h-12
            ap='p'
        if(h<10):
            h='0'+'%d'%h
            timeend = h+':'+M+ap
        else :
            timeend = '%d'%h+':'+M+ap
        match_matchtime = date+' '+timeend

        if ti[25] =='':ti[25] ="0" 
        if ti[27] =='':ti[27] ="0"
        if ti[24] =='':ti[24] ="0" 
        if ti[26] =='':ti[26] ='0'
        if ti[28] =='':ti[28] ='0'   
        if ti[29] =='':ti[29] ='0'  
        if ti[30] =='':ti[30] ='0'  
        if ti[34] =='': ti[34] ='0'  
        if ti[15] =='': ti[15] ='0'  
        if ti[16] =='': ti[16] ='0' 
        if ti[13] =='': ti[13] ='0'  
        if ti[14] =='': ti[14] ='0' 
        if ti[17] =='': ti[17] ='0'  
        if ti[31] =='': ti[31] ='0'  
        if ti[32] =='': ti[32] ='0'  
        if ti[33] =='': ti[33] ='0'  
        if ti[36] =='': ti[36] ='0' 
        if ti[37] =='': ti[37] ='0' 
        if ti[38] =='': ti[38] ='0' 
        if ti[20] =='': ti[20] ='0' 
        if ti[21] =='': ti[21] ='0' 
        if ti[23] =='': ti[23] ='0'
        if ti[9] =='': ti[9] ='0'
        if ti[10] =='': ti[10] ='0'
        if ti[35] =='': ti[35] ='0'
        ti[11] = ti[11].replace('O','')
        ti[11] = ''.join(ti[11].split()) 
        ti[10] = ''.join(ti[10].split())
        ti[8] = ''.join(ti[8].split())  
        ti[25] = ''.join(ti[25].split())  
        ti[22] = ''.join(ti[22].split())
         #==================================================S
        dx	=	get_HK_ior(float(ti[9]),float(ti[10]))
        ti[9]	=	str(dx[0])
        ti[10]	=	str(dx[1])
        dx	=	get_HK_ior(float(ti[23]),float(ti[24]))
        ti[23]	=	str(dx[0])
        ti[24]	=	str(dx[1])
        dx	=	get_HK_ior(float(ti[13]),float(ti[14]))
        ti[13]	=	str(dx[0])
        ti[14]	=	str(dx[1])
        dx	=	get_HK_ior(float(ti[27]),float(ti[28]))
        ti[27]	=	str(dx[0])
        ti[28]	=	str(dx[1])
        ti[2] = ''.join(ti[2].split())
        ti[5] = ''.join(ti[5].split())
        ti[6] = ''.join(ti[6].split())
        statusnn =0
        con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
        cur = con.cursor()
        print '插入数据1_public.insert into bet_match';
        sql = "insert into bet_match( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_Hr_ShowType,Match_BRpk,Match_BHo,Match_BAo,Match_Bdpl,Match_Bxpl,Match_BDxpk,Match_NowScore,Match_CoverDate,Match_MatchTime,Match_HRedCard,Match_GRedCard,Match_BzM,Match_BzG,Match_BzH,Match_Bmdy,Match_Bgdy,Match_Bhdy,Match_HalfId,Match_MasterID,Match_GuestID,Match_JS,Match_LstTime,iPage) values('"+ti[0]+"','"+date+"','"+ti[1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','2','"+ti[7]+"','"+ti[9]+"','"+ti[10]+"','"+ti[8]+"','"+ti[11]+"','"+ti[14]+"','"+ti[13]+"','"+ti[7]+"','"+ti[22]+"','"+ti[23]+"','"+ti[24]+"','"+ti[28]+"','"+ti[27]+"','"+ti[25]+"','"+ti[18]+":"+ti[19]+"',now(),'"+str(match_matchtime)+"','"+ti[29]+"','"+ti[30]+"','"+ti[33]+"','"+ti[34]+"','"+ti[35]+"','"+ti[36]+"','"+ti[37]+"','"+ti[38]+"','"+ti[20]+"','"+ti[3]+"','"+ti[4]+"',"+'%d'%js+",now(),1)"
        print sql
        try:
            cur.execute(sql)
            con.commit()
            
        except MySQLdb.Error, e:
            if('Duplicate' in e[1]):
                statusnn = 1
            print e 
        cur.close()
        con.close() 
        print   statusnn
        if(statusnn ==1):
            if(status==1):
                con_pp = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
                cur_pp = con_pp.cursor()
                print '更新滚球半场数据2_public.insert into bet_match';
                sql = "update bet_match set Match_MasterID='"+ti[3]+"',Match_HalfId='"+ti[20]+"',Match_GuestID='"+ti[4]+"',Match_Time='"+ti[1]+"',Match_Type='2',Match_ShowType='"+ti[7]+"',Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_RGG='"+ti[8]+"',Match_BzM='"+ti[33]+"',Match_BzG='"+ti[34]+"',Match_BzH='"+ti[35]+"',MB_Inball_HR='"+ti[18]+"',TG_Inball_HR='"+ti[19]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_Hr_ShowType='"+ti[7]+"',Match_BRpk='"+ti[22]+"',Match_BHo='"+ti[23]+"',Match_BAo='"+ti[24]+"',Match_Bdpl='"+ti[28]+"',Match_Bxpl='"+ti[27]+"',Match_BDxpk='"+ti[25]+"',Match_Bmdy='"+ti[36]+"',Match_Bgdy='"+ti[37]+"',Match_Bhdy='"+ti[38]+"',Match_NowScore='"+ti[18]+':'+ti[19]+"',Match_OverScore='"+ti[18]+':'+ti[19]+"',Match_HRedCard='"+ti[29]+"',Match_GRedCard='"+ti[30]+"',Match_JS='"+'%d'%js+"',Match_LstTime=now() WHERE Match_ID='"+ti[0]+"' AND Match_StopUpdateg=0"                
                print sql
                cur_pp.execute(sql) 
                con_pp.commit()
                cur_pp.close()
                con_pp.close()
                print 'COMMIT上半场数据3_public.insert into bet_match';
                #cur.close()
                #con.close()
                con_p = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_P,charset='utf8' )
                cur_p = con_p.cursor()
                try:
                    sql = "Update `k_bet` set MB_Inball='"+ti[18]+"',TG_Inball='"+ti[19]+"' where  Match_ID='"+ti[0].encode('utf-8')+"' and point_column in ('match_bho','match_bao','match_bdpl','match_bxpl','match_bmdy','match_bgdy','match_bhdy') and  lose_ok=1 and status=0"                                                                     
                    print sql
                    cur_p.execute(sql)
                    sql = "Update `k_bet_cg` set MB_Inball='"+ti[18]+"',TG_Inball='"+ti[19]+"' where  Match_ID='"+ti[0].encode('utf-8')+"'  and point_column in ('match_bho','match_bao','match_bdpl','match_bxpl','match_bmdy','match_bgdy','match_bhdy') and status=0  "                                                                     
                    print sql
                    cur_p.execute(sql)
                except MySQLdb.Error, e:
                    print  e
                print 'COMMIT上半场数据5';
                con_p.commit()
                cur_p.close()
                con_p.close()
            else:
                print 'ddddd'
                con_r = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
                cur_rr = con_r.cursor()  
                sql = "update bet_match set Match_MasterID='"+ti[3]+"',Match_HalfId='"+ti[20]+"',Match_GuestID='"+ti[4]+"',Match_Time='"+ti[1]+"',Match_Type='2',Match_ShowType='"+ti[7]+"',Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_RGG='"+ti[8]+"',Match_BzM='"+ti[33]+"',Match_BzG='"+ti[34]+"',Match_BzH='"+ti[35]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_Hr_ShowType='"+ti[7]+"',Match_BRpk='"+ti[22]+"',Match_BHo='"+ti[23]+"',Match_BAo='"+ti[24]+"',Match_Bdpl='"+ti[28]+"',Match_Bxpl='"+ti[27]+"',Match_BDxpk='"+ti[25]+"',Match_Bmdy='"+ti[36]+"',Match_Bgdy='"+ti[37]+"',Match_Bhdy='"+ti[38]+"',Match_NowScore='"+ti[18]+':'+ti[19]+"',Match_OverScore='"+ti[18]+':'+ti[19]+"',Match_HRedCard='"+ti[29]+"',Match_GRedCard='"+ti[30]+"',Match_JS='"+'%d'%js+"',Match_LstTime=now() WHERE Match_ID='"+ti[0]+"' AND Match_StopUpdateg=0"
                print sql
                try:
                    cur_rr.execute(sql)
                    con_r.commit()
                except MySQLdb.Error, e:
                    print e    
                cur_rr.close()
                con_r.close()
                print 'ffff';

    print '流程走完';


def GQbd28(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    json_data = '[['+str(time.time())+'],'+json.dumps(title)+']'
    RedisClent.set('LQGQ_JSON',json_data)
    #RedisCache().set_data('LQGQ_JSON', json_data)
    for ti in title:
        if('测试' in ti[2]):
            continue
        datey = time.strftime('%Y-%m-%d',time.localtime(time.time()))
        date = time.strftime('%m-%d',time.localtime(time.time()))
        uptime =0
        if(('p' in ti[1]) and (not '12' in ti[1])):
            uptime = 12*60*60
        text5 = ti[1][:-1]
        text5 = text5+':00'
        text5 =  datey+' '+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        CoverDate=text5
        HgDate = date+' '+ti[1]
        ti[11] = ti[11].replace('O','')
        ti[11] = ''.join(ti[11].split())
        if ti[9] =='': ti[9] ='0' 
        if ti[10] =='': ti[10] ='0'
        if ti[11] =='': ti[11] ='0'
        if ti[13] =='': ti[13] ='0'
        if ti[14] =='': ti[14] ='0'
        ti[10] = ''.join(ti[10].split())
        ti[8] = ''.join(ti[8].split())
        ti[2] = ''.join(ti[2].split())
        ti[5] = ''.join(ti[5].split())
        ti[6] = ''.join(ti[6].split()) 
        sql = "insert into lq_match(Match_ID,Match_Time,Match_Date,Match_Name,Match_Master,Match_Guest,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_CoverDate,Match_MatchTime,Match_MasterID,Match_GuestID,Match_LstTime,iPage) values ('"+ti[0]+"','"+ti[1]+"','"+date+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"',2,'"+ti[7]+"','"+ti[9]+"','"+ti[10]+"','"+ti[8]+"','"+ti[11]+"','"+ti[14]+"','"+ti[13]+"','"+CoverDate+"','"+HgDate+"','"+ti[3]+"','"+ti[4]+"',now(),1)"
        print sql
        try:
            cur.execute(sql)
            con.commit()
        except MySQLdb.Error, e:
            sql = "update lq_match set Match_Time='"+ti[1]+"',Match_Type=2,Match_ShowType='"+ti[7]+"',Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_RGG='"+ti[8]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_MasterID='"+ti[3]+"',Match_GuestID='"+ti[4]+"',Match_LstTime=now() WHERE Match_ID='"+ti[0]+"' AND Match_StopUpdateg=0"
            print sql
            cur.execute(sql) 
            con.commit()
    con.commit()
    cur.close()     
    con.close()
                    
operator = {27:GQbd27,28:GQbd28}

class spider(threading.Thread):
    def run(self):
        while 1:
            try:
                job = jobs.get(True,1)
                singlelock.acquire()
                page =getPage(domain+job[1])
                page = int(page[0]) 
                title = getTitle(job[0],domain+job[1],uid,page)
                info.put([job[0],title], block=True, timeout=5)
                singlelock.release()
                jobs.task_done()
            except:
                break;
  
if __name__ == '__main__':
#def run():
    con = None
    urls = []
    try:
        rows=[
                 [27,'/app/member/FT_browse/body_var.php?rtype=re&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&uid='],
                 [28,'/app/member/BK_browse/body_var.php?rtype=re_main&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&uid='],
             ]
        for row in rows:
            # print row
            urls.append([row[0],row[1]])
        workerbee(urls)
        while not info.empty():
            print info.get()
    finally:
        if con:
            con.close()
# def runTask(func, day=0, hour=0, min=0, second=0):
#     nowtime = datetime.datetime.now()
#     strnow = nowtime.strftime('%Y-%m-%d %H:%M:%S')
#     print "now:",strnow
#     period = datetime.timedelta(days=day, hours=hour, minutes=min, seconds=second)
#     next_time = nowtime + period
#     strnext_time = next_time.strftime('%Y-%m-%d %H:%M:%S')
#     print "next run:",strnext_time
#     while True:
#         iter_now = datetime.datetime.now()
#         iter_now_time = iter_now.strftime('%Y-%m-%d %H:%M:%S')
#         if str(iter_now_time) >= str(strnext_time):
#             print "start work: %s" % iter_now_time
#             func()
#             print "task done."
#             iter_time = iter_now + period
#             strnext_time = iter_time.strftime('%Y-%m-%d %H:%M:%S')
#             print "next_iter: %s" % strnext_time
#            continue
#run()
#runTask(run,0,0,0,1)