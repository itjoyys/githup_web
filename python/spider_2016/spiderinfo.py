# -*- coding: utf-8 -*-
#encoding=utf-8

import datetime
import time
import threading
import Queue
import urllib2
import urllib
import re
import MySQLdb
import chardet
import string
import json
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



THREAD_LIMIT = 13
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
     
    
def getTitle(Uid,url,uid,time=10):
    req = urllib2.Request(domain+"/app/member/mem_online.php?uid="+uid,None,i_headers)
    urllib2.urlopen(req,None,timeout=time)
    #urllib2.urlopen(domain+"/app/member/mem_online.php?uid="+uid,timeout=time)  
    print url+uid
    req = urllib2.Request(url+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=time) 
    #response = urllib2.urlopen(url+uid,timeout=time)
    html = response.read()
    response.close()
    operator.get(Uid)(html)

def GQbd50(html): 
    reg = r'color_bg([\d\D]*?)<\/tr>'
    html = re.compile(reg).findall(html)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    i = 0
    for ti in html:
        if(i>4):
            break
        reg = r'm_cen">([\d\D]*?)</td>'
        time = re.compile(reg).findall(ti)
        timecc = '20'+time[1]+' 00:00:00'
        reg = r'm_lef">([\d\D]*?)</td>'
        notice = re.compile(reg).findall(ti) 
        print "select * from site_notice where notice_content = '"+notice[0]+"'"
        cur.execute("select * from site_notice where notice_content = '"+notice[0]+"'")
        rows = cur.fetchall()
        if not rows: 
            try:
                sql = "insert into site_notice ( sid,notice_title,notice_content,notice_cate,notice_date,notice_state ) values ('0','体育赛事公告','"+notice[0]+"','s_p','"+timecc+"',0)"
                print sql
                cur.execute(sql)
                con.commit()
            except MySQLdb.Error, e:
                print e
                print sql
        i+=1
    con.commit()
    cur.close()     
    con.close()
                    
operator = {50:GQbd50}

class spider(threading.Thread):
    def run(self):
        while 1:
            try:
                job = jobs.get(True,1)
                singlelock.acquire()
                title = getTitle(job[0],domain+job[1],uid)
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
                 [50,'/app/member/scroll_history.php?langx=zh-cn&uid='],
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
#             continue
# #run()
# runTask(run,0,0,0,1)