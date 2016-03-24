'''
Created on 2015-3-24

@author: Administrator
'''
# -*- coding: utf-8 -*-
#encoding=utf-8


import threading
import json
import Queue
import urllib2
import re
import MySQLdb
import chardet
import time,datetime
import globalConfig
import redis
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_NAME = globalConfig.DB_NAME

HOST = globalConfig.HOST
RedisClent = redis.Redis(host=HOST, port=6379)   
#
#
import sys
num =0
for i in range(1, len(sys.argv)):
    num = sys.argv[i]
if num==0:
    num=5
    

THREAD_LIMIT = 8
jobs = Queue.Queue(5)
singlelock = threading.Lock()
info = Queue.Queue()
  
i_headers = {"User-Agent": "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116",
             "Referer": 'http://www.baidu.com/'}
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

     
    
def getTitle(Uid,url,time=10):
 
    if Uid==13 or Uid==14 :
        req = urllib2.Request(url)
        req.add_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        req.add_header('User-Agent','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116')
        req.add_header('Referer',"http://kuai3.cjcp.com.cn/")            
    else:
        req = urllib2.Request(url,headers=i_headers)
    response = urllib2.urlopen(req,timeout=time)
    html = response.read()
    print Uid
    response.close()
    tables = {13:'js_k3_auto',14:'jl_k3_auto'}
    operator.get(Uid)(html,tables[Uid])              
    return html                

# def Cbet11(html,table): 
#     infoencode = chardet.detect(html).get('encoding','utf-8')
#     html = html.decode(infoencode,'ignore').encode('utf-8')
#     html = json.loads(html)
#     for title in html:
#         print html[title]
#         con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#         cur = con.cursor()
#         ball = html[title]['number'].split(',')
#         cur.execute("select * from "+table+" where qishu = '20"+title+"'")
#         rows = cur.fetchall()
#         if not rows: 
#             cur.execute("insert into "+table+" (  qishu,datetime,ok,ball_1,ball_2,ball_3 ) values ('20"+title+"','"+html[title]['dateline']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"' )")                                             
#             con.commit()
#             cur.close()     
#             con.close()
             
def Cbet11(html,table):
    infoencode = chardet.detect(html).get('encoding','utf-8')
    html = html.decode(infoencode,'ignore').encode('utf-8')
    html = json.loads(html)
    html = html['data']
    oldnum =0
    redisarray = []
    for title in html:
        con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
        cur = con.cursor()
        qishu = title['expect']
        qishuint = int(qishu)
        ball = title['opencode'].split(',')
        cur.execute("select * from "+table+" where qishu = '"+qishu+"'")
        rows = cur.fetchall()
        if(qishuint>oldnum):
            oldnum = qishuint
            redisarray = [qishu,title['opentime'],ball[0],ball[1],ball[2]]
        if not rows: 
            cur.execute("insert into "+table+" (  qishu,datetime,ok,ball_1,ball_2,ball_3 ) values ('"+qishu+"','"+title['opentime']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"' )")                                             
            con.commit()
            cur.close()     
            con.close()

    if  redisarray:
        print 'ZDREDIS START'
        if(table =='js_k3_auto'):
            table='c_auto_13'
        else:
            table='c_auto_14'
        redisarray = json.dumps(redisarray)
        RedisClent.set(table+'_result',redisarray)
        print 'ZDREDIS END'        
    
    
operator = {13:Cbet11,14:Cbet11}

class spider(threading.Thread):
    def run(self):
        while 1:
            try:
                job = jobs.get(True,1)
                singlelock.acquire()
                title = getTitle(job[0],job[1])
                info.put([job[0],title], block=True, timeout=5)
                singlelock.release()
                jobs.task_done()
            except:
                break;
  
#if __name__ == '__main__':
def run():
    con = None
    urls = []
    try:
        rows=[
#                  [9,'http://cp.swlc.sh.cn/notice/ssl/index.html'] # 上海时时乐
#                 [13,'http://api.caipiaokong.com/lottery/?name=jsks&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3'],  #江苏快3
#                 [14,'http://api.caipiaokong.com/lottery/?name=jlks&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3'],  #吉林快3
                 [13,'http://c.apiplus.net/newly.do?token=6d8caf8c25bc44f5&code=jsk3&format=json&rows='+str(num)],  #江苏快3
                 [14,'http://c.apiplus.net/newly.do?token=6d8caf8c25bc44f5&code=jlk3&format=json&rows='+str(num)],  #吉林快3
             ]
        for row in rows:
            #print row
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
runTask(run,0,0,0,30)