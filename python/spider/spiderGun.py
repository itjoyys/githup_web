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
    try:
        req = urllib2.Request(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,None,headers)
        response = urllib2.urlopen(req,None,timeout=10) 
        #response = urllib2.urlopen(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,timeout=10)
    except Exception as e:
        print e
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
        print t.isAlive()
        t.thread_stop = True 
    for i in inputlist:
        try:
            jobs.put(i, block=True, timeout=10)
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
    #urllib2.urlopen(domain+"/app/member/mem_online.php?uid="+uid,timeout=time) 
    req = urllib2.Request(url+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=time) 
    #response = urllib2.urlopen(url+uid,timeout=time) 
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
    print 'ZDREDIS START';
    json_data = '[['+str(time.time())+'],'+json.dumps(title)+']'
    RedisClent.setex('ZQGQ_JSON',json_data,30)
    print 'ZDREDIS END';
 


def GQbd28(title): 
#     con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#     cur = con.cursor()
    json_data = '[['+str(time.time())+'],'+json.dumps(title)+']'
    RedisClent.setex('LQGQ_JSON',json_data,30)
                    
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
                print 'ccsss'
                break;
  
#if __name__ == '__main__':
def run():
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
runTask(run,0,0,0,15)