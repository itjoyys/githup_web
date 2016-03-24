'''
Created on 2015-3-24

@author: Administrator
'''
# -*- coding: utf-8 -*-
#encoding=utf-8


import threading
import json
import Queue
import sys
import urllib2
import urllib
import cookielib  
import os  
import re
import MySQLdb
import chardet
#import balance1,balance2,balance3,balance4,balance5,balance6,balance8
import time,datetime
from subprocess import Popen, PIPE
import globalConfig
import string

team = '英格兰超级联赛乙组U21'
team = ''.join(team.split())
print team
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_NAME = "cyj_public"
DB_NAME_P = "cyj_private"  
#

#

zmz =[1,32,33,32,12]
num =23
ctail =[]
for z in zmz+[num]:
    ctail+=[str(z)[-1:]]
ctail=set(ctail)
print ctail
quit()
num = 9
print str(num)[-1:]
zmz = [3,4,5,6]
for z in zmz+[num]:
    print z
quit()
cbetballtype='正码1,正码2,'
cbetball = '单,双,'
cbetballtype=cbetballtype.strip(',').split(',')
cbetball    =cbetball.strip(',').split(',')
print cbetballtype
print cbetball
print int('02')
quit()
if(49 in [49L, 25L, 74L, 34L, 47L, 18L, 69L, 58L, 31L, 6L, 42L, 65L, 8L, 55L, 66L, 26L, 33L, 14L, 78L, 32L]):
    print '中了'
    
quit()

THREAD_LIMIT = 7
jobs = Queue.Queue(5)
singlelock = threading.Lock()
info = Queue.Queue()
  
i_headers = {"User-Agent": "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116",
             "Referer": 'http://www.baidu.com/'}


con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' ) 
cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )  
cur.execute("select * from c_auto_2 where qishu = 20150422058 order by id desc limit 1 ")
rs = cur.fetchall()
hm  = [rs[0]['ball_1'],rs[0]['ball_2'],rs[0]['ball_3'],rs[0]['ball_4'],rs[0]['ball_5']]
con.commit()
cur.close()     
con.close()

con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME_P,charset='utf8' )
cur = con.cursor()
cur.execute("select * from c_bet where type='重庆时时彩' and js=0 and qishu=20150422058 order by addtime asc")
rows = cur.fetchall()
print rows
print 'cccc'
row =rows[0]
cur.execute("select * from k_user where uid="+'%d'%row[2])
users = cur.fetchall()  
user =users[0]
print 'cccccccccccccccccccc------------------------------'
print row[0]
#sql = " insert into k_user_cash_record (site_id ,source_id,uid,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num) values ('"+user[42]+"','"+'%d'%row[0]+"','"+'%d'%row[2]+"',14,1,'"+'%d'%row[12]+"','"+'%d'%user[7]+unicode("',now(),'期數：20150422058 類型：",'utf-8')+row[5]+"',0) "
print row[1]
print row[16]
print user[13]
print row[6]
print row[2]

sql = " insert into k_user_cash_record (site_id ,source_id,uid,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,discount_num) values ('"+user[42]+"',"+str(row[0])+",'"+str(row[0])+"',15,1,'"+str(row[16])+"','"+str(user[7])+"',now(),"+unicode("'自动结算期数：",'utf-8')+str(row[6])+unicode(" 類型：",'utf-8')+str(row[2])+"',0) "
print sql
cur.execute(sql)

quit()
import threading
#    my_threading = threading.Thread(target=func, args=(param1, param2)).start() 
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
    jobs.join()                 # This command waits for all threads to finish.

     
    
def getTitle(Uid,url,time=10):
    if Uid==2:
        req = urllib2.Request(url)
        req.add_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        req.add_header('X-Requested-With','XMLHttpRequest')
        req.add_header('User-Agent','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116')
        req.add_header('Referer',"http://baidu.lecai.com/")        
    elif Uid==3 or Uid==4:
        req = urllib2.Request(url)
        req.add_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        req.add_header('User-Agent','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116')
        req.add_header('Referer',"http://baidu.lecai.com/")            
    else:
        req = urllib2.Request(url,headers=i_headers)
    response = urllib2.urlopen(req,timeout=time)
    html = response.read()
    print Uid
    response.close()
    operator.get(Uid)(html)    
    return html

def Cbet1(html): 
    html = ''.join(html.split())
    reg = r'c-border"><div>([\d\D]*)<\/span><\/div><div>'
    title = re.compile(reg).findall(html)
    infoencode = chardet.detect(title[0]).get('encoding','utf-8')
    title = title[0].decode(infoencode,'ignore').encode('utf-8')
    title = title+'</span>'
    qishu = re.compile(r'第(.*?)期').findall(title)
    timec = re.compile(r'开奖日期：(.*?)<\/div><divclass').findall(title)
    ball = re.compile(r'c-iconc-icon-ball-redop_caipiao_ball_redc-gap-right-small">(.*?)<\/span').findall(title)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec[0],"%Y-%m-%d%H:%M:%S")
    time_sec_float = time.mktime(d.timetuple())
    timenow=int(time.time())
    status ='0'
    if(time_sec_float<=timenow):
        status='1'
    timec[0] = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(time_sec_float))

    qishu[0]=qishu[0].replace(" ", "")
    cur.execute("select * from c_auto_1 where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `c_auto_1` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
        os.popen(r'"python" /home/python/spider/balance1.py')       
#        balance1.balance1(qishu[0])

def Cbet2(html):
    html = json.loads(html)
    title=html['data']
    reg = r'<tr >([\d\D]*?)</tr>'
    title = re.compile(reg).findall(title)
    title=title[0]
    reg = r'<td>([\d\D]*?)</td>'
    title = re.compile(reg).findall(title) 
#    qishu = title[0].replace('期','')
    qishu = title[0][0:11]
    print qishu
    timec = title[1]
    time_sec_float =int(time.time())
    today = time.strftime("%Y-%m-%d",  time.localtime(time_sec_float))
    timec = today+' '+timec+':00'
    ball = re.compile(r'red">(.*?)<\/span').findall(title[2])
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec,"%Y-%m-%d %H:%M:%S")
    time_sec_float = time.mktime(d.timetuple())
    timenow=int(time.time())
    status ='0'
    if(time_sec_float<=timenow):
        status='1'
    cur.execute("select * from c_auto_2 where qishu = '"+qishu+"'")
    rows = cur.fetchall()
    Popen('python /home/python/spider/balance2.py') 
    if not rows: 
        cur.execute("insert into `c_auto_2` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5) values ('"+qishu+"','"+timec+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
        os.popen(r'"python" /home/python/spider/balance2.py')    
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu+"&cate=cqssc"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#        balance2.balance2(qishu)
    
def Cbet3(html): 
    ball = re.compile(r'{"red":\[([\d\D]*?)],').findall(html)
    ball = re.compile(r'"([\d\D]*?)"').findall(ball[0])
    timec = re.compile(r'latest_draw_time = \'([\d\D]*?)\';').findall(html)
    qishu = re.compile(r'latest_draw_phase = \'([\d\D]*?)\';').findall(html)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec[0],"%Y-%m-%d %H:%M:%S")
    time_sec_float = time.mktime(d.timetuple())
    timenow=int(time.time())
    status ='0'
    if(time_sec_float<=timenow):
        status='1'
    cur.execute("select * from c_auto_3 where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `c_auto_3` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=pk10"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#        balance3.balance3(qishu[0])
    
def Cbet4(html): 
    ball = re.compile(r'{"red":\[([\d\D]*?)],').findall(html)
    ball = re.compile(r'"([\d\D]*?)"').findall(ball[0])
    timec = re.compile(r'latest_draw_time = \'([\d\D]*?)\';').findall(html)
    qishu = re.compile(r'latest_draw_phase = \'([\d\D]*?)\';').findall(html)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec[0],"%Y-%m-%d %H:%M:%S")
    time_sec_float = time.mktime(d.timetuple())
    timenow=int(time.time())
    status ='0'
    if(time_sec_float<=timenow):
        status='1'
    cur.execute("select * from c_auto_4 where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `c_auto_4` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=cqklsf"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#        balance4.balance4(qishu[0])
                
def Cbet5(html): 
    reg = r'kjgg\"><b([\d\D]*)<\/span><\/div>'
    title = re.compile(reg).findall(html)
    infoencode = chardet.detect(title[0]).get('encoding','utf-8')
    title = title[0].decode(infoencode,'ignore').encode('utf-8')
    title = title+'</span>'
    qishu = re.compile(r'第(.*?)期').findall(title)
    timec = re.compile(r'时间：(.*?)<br').findall(title)
    ball = re.compile(r'red_ball">(.*?)<\/span').findall(title)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec[0],"%Y-%m-%d %H:%M")
    time_sec_float = time.mktime(d.timetuple())
    timenow=int(time.time())
    status ='0'
    if(time_sec_float<=timenow):
        status='1'
    qishu[0]=qishu[0].replace(" ", "")
    if not ball[0]=='-':        
        cur.execute("select * from c_auto_5 where qishu = '"+qishu[0]+"'")
        rows = cur.fetchall()
        if not rows: 
            cur.execute("insert into `c_auto_5` ( qishu,datetime,ok,ball_1,ball_2,ball_3) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"' )")                                               
            con.commit()
            cur.close()     
            con.close()
#             pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=fc3d"
#             response = urllib2.urlopen(pkurl,timeout=10)
#             html = response.read()
#             print html
#             print 'ccccc'
#             response.close()
#            balance5.balance5(qishu[0])
    
def Cbet6(html):
    html = ''.join(html.split())
    reg = r'kjgg\"><b([\d\D]*)<\/span><\/div>'
    title = re.compile(reg).findall(html)
    infoencode = chardet.detect(title[0]).get('encoding','utf-8')
    title = title[0].decode(infoencode,'ignore').encode('utf-8')
    title = title+'</span>'
    qishu = re.compile(r'第(.*?)期').findall(title)
    timec = re.compile(r'时间：(.*?)<br').findall(title)
    ball = re.compile(r'red_ball">(.*?)<\/span').findall(title)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec[0],"%Y-%m-%d%H:%M")
    time_sec_float = time.mktime(d.timetuple())
    timenow=int(time.time())
    status ='0'
    if(time_sec_float<=timenow):
        status='1'
    timec[0] = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(time_sec_float))
    qishu[0]=qishu[0].replace(" ", "")
    cur.execute("select * from c_auto_6 where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    
    if not rows: 
        cur.execute("insert into `c_auto_6` ( qishu,datetime,ok,ball_1,ball_2,ball_3) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=pls"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#        balance6.balance6(qishu[0])

def Cbet8(html): 
    ball = re.compile(r'{"red":\[([\d\D]*?)],').findall(html)
    ball = re.compile(r'"([\d\D]*?)"').findall(ball[0])
    timec = re.compile(r'latest_draw_time = \'([\d\D]*?)\';').findall(html)
    qishu = re.compile(r'latest_draw_phase = \'([\d\D]*?)\';').findall(html)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec[0],"%Y-%m-%d %H:%M:%S")
    time_sec_float = time.mktime(d.timetuple())
    timenow=int(time.time())
    status ='0'
    if(time_sec_float<=timenow):
        status='1'
    cur.execute("select * from c_auto_8 where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `c_auto_8` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10,ball_11,ball_12,ball_13,ball_14,ball_15,ball_16,ball_17,ball_18,ball_19,ball_20) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"','"+ball[10]+"','"+ball[11]+"','"+ball[12]+"','"+ball[13]+"','"+ball[14]+"','"+ball[15]+"','"+ball[16]+"','"+ball[17]+"','"+ball[18]+"','"+ball[19]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=bjkl8"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#        balance8.balance8(qishu[0])   
        
operator = {1:Cbet1,2:Cbet2,3:Cbet3,4:Cbet4,5:Cbet5,6:Cbet6,8:Cbet8}

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
  
if __name__ == '__main__':
    con = None
    urls = []
    try:
        rows=[
#                  [1,'https://www.baidu.com/s?wd=%E5%B9%BF%E5%B7%9E%E5%BF%AB%E4%B9%90%E5%8D%81%E5%88%86'],
                  [2,'http://baidu.lecai.com/lottery/draw/ajax_get_latest_draw_html.php?lottery_type=200'],
#                   [3,'http://baidu.lecai.com/lottery/draw/view/557'],
#                   [4,'http://baidu.lecai.com/lottery/draw/view/566'],
#                   [5,'http://caipiao.163.com/order/3d/'],
#                   [6,'http://caipiao.163.com/order/pl3/'],                 
#                   [8,'http://baidu.lecai.com/lottery/draw/view/543'],            
             ]
        for row in rows:
            # print row
            urls.append([row[0],row[1]])
        workerbee(urls)
        while not info.empty():
            print 'end'
            quit()
#              print info.get()
    finally:
        if con:
            con.close()