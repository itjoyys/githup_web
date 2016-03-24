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
    if Uid==2:
        req = urllib2.Request(url)
        req.add_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        req.add_header('X-Requested-With','XMLHttpRequest')
        req.add_header('User-Agent','Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5')
        req.add_header('Referer',"http://api.caipiaokong.com/")        
    elif Uid==3 or Uid==4:
        req = urllib2.Request(url)
        req.add_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        req.add_header('User-Agent','Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5')
        req.add_header('Referer',"http://api.caipiaokong.com/")            
    elif Uid==10 or Uid==11 or Uid==12:
        req = urllib2.Request(url)
        req.add_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        req.add_header('User-Agent','Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5')
        req.add_header('Referer',"http://api.caipiaokong.com/")            
    else:
        req = urllib2.Request(url,headers=i_headers)
    response = urllib2.urlopen(req,timeout=time)
    html = response.read()
    print Uid
    response.close()
    if(Uid>9 or Uid==2):
        tables = {2:'c_auto_2',10:'c_auto_10',11:'jx_ssc_auto',12:'xj_ssc_auto'}
        operator.get(Uid)(Uid,html,tables[Uid])    
    else:
        operator.get(Uid)(html)           
    return html

#生肖
def zodiac(nianfen,num):
    #2014    
    shu = [7,19,31,43]
    niu = [6,18,30,42]
    hu = [5,17,29,41]
    tu = [4,16,28,40]
    long = [3,15,27,39]
    she = [2,14,26,38]
    ma = [1,13,25,37,49]
    yang = [12,24,36,48]
    hou = [11,23,35,47]
    ji = [10,22,34,46]
    gou = [9,21,33,45]
    zhu = [8,20,32,44]
    arr=[shu,niu,hu,tu,long,she,ma,yang,hou,ji,gou,zhu]

    shuxiang=["鼠","牛","虎","兔","龙","蛇","马","羊","猴","鸡","狗","猪"]
    count=len(shuxiang)
    xu = int(nianfen)-2014#变化参数 
    i=0
    while (i<count):
        xuhao =i+xu
        if(xuhao  >= 12):
            xuhao = xuhao%12 
        if(int(num) in arr[i]):   
            return shuxiang[xuhao]
        i+=1
        

# def Cbet2(html):
#     html = json.loads(html)
#     title=html['data']
#     reg = r'<tr >([\d\D]*?)</tr>'
#     title = re.compile(reg).findall(title)
#     title=title[0]
#     reg = r'<td>([\d\D]*?)</td>'
#     title = re.compile(reg).findall(title) 
# #    qishu = title[0].replace('期','')
#     qishu = title[0][0:11]
#     print qishu
#     timec = title[1]
#     time_sec_float =int(time.time())
#     today = time.strftime("%Y-%m-%d",  time.localtime(time_sec_float))
#     timec = today+' '+timec+':00'
#     ball = re.compile(r'red">(.*?)<\/span').findall(title[2])
#     con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#     cur = con.cursor()
#     d = datetime.datetime.strptime(timec,"%Y-%m-%d %H:%M:%S")
#     time_sec_float = time.mktime(d.timetuple())
#     timenow=int(time.time())
#     status ='0'
#     cur.execute("select * from c_auto_2 where qishu = '"+qishu+"'")
#     rows = cur.fetchall()
#     if not rows: 
#         cur.execute("insert into `c_auto_2` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5) values ('"+qishu+"','"+timec+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"' )")                                             
#         con.commit()
#         cur.close()     
#         con.close()
        
# def Cbet2(UId,html,table):
#     infoencode = chardet.detect(html).get('encoding','utf-8')
#     html = html.decode(infoencode,'ignore').encode('utf-8')
#     html = json.loads(html)
#     for title in html:
#         print html[title]
#         qishu = title
#         if(UId == 12):
#             qihaoend = title[-2:]
#             qihaoend = '0'+qihaoend
#             qihaostart = title[0:-2]
#             qishu = qihaostart+qihaoend
#         con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#         cur = con.cursor()
#         ball = html[title]['number'].split(',')
#         cur.execute("select * from "+table+" where qishu = '"+qishu+"'")
#         rows = cur.fetchall()
#         if not rows: 
#             cur.execute("insert into "+table+" (  qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5) values ('"+qishu+"','"+html[title]['dateline']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"' )")                                             
#             con.commit()
#             cur.close()     
#             con.close()

def Cbet2(UId,html,table):
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
            redisarray = [qishu,title['opentime'],ball[0],ball[1],ball[2],ball[3],ball[4]]
        if not rows: 
            cur.execute("insert into "+table+" ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5) values ('"+qishu+"','"+title['opentime']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"' )")                                             
            con.commit()
            cur.close()     
            con.close() 
            
    if  redisarray:
        print 'ZDREDIS START'
        if(table =='jx_ssc_auto'):
            table='c_auto_11'
        else:
            table='c_auto_12'
        redisarray = json.dumps(redisarray)
        RedisClent.set(table+'_result',redisarray)
        print 'ZDREDIS END'
        
def Cbet3(html): 
    infoencode = chardet.detect(html).get('encoding','utf-8')
    html = html.decode(infoencode,'ignore').encode('utf-8')
    html = json.loads(html)
    for title in html:
        print html[title]
        con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
        cur = con.cursor()
        ball = html[title]['number'].split(',')
        cur.execute("select * from c_auto_3 where qishu = '"+title+"'")
        rows = cur.fetchall()
        if not rows: 
            cur.execute("insert into `c_auto_3` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10) values ('"+title+"','"+html[title]['dateline']+"','0','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"' )")                                             
            con.commit()
            cur.close()     
            con.close()
   
def Cbet4(html): 
    infoencode = chardet.detect(html).get('encoding','utf-8')
    html = html.decode(infoencode,'ignore').encode('utf-8')
    html = json.loads(html)
    for title in html:
        print html[title]
        con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
        cur = con.cursor()
        ball = html[title]['number'].split(',')
        cur.execute("select * from c_auto_4 where qishu = '"+title+"'")
        rows = cur.fetchall()
        if not rows: 
            cur.execute("insert into `c_auto_4` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values ('"+title+"','"+html[title]['dateline']+"','0','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"' )")                                             
            con.commit()
            cur.close()     
            con.close()

def Cbet9(html): 
    qishu = re.compile(r'cr\">([\d\D]*?)<\/em>').findall(html)
    qishu = qishu[0].split('-')
    qishu = qishu[0]+qishu[1]
    print qishu
    timec = re.compile(r'日期：([\d\D]*?)<\/p>').findall(html)
    print timec
    ball = re.compile(r'redball\">([\d\D]*?)<\/em>').findall(html)
    print ball
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    d = datetime.datetime.strptime(timec[0],"%Y年%m月%d日")
    time_sec_float = time.mktime(d.timetuple()) 
    timec = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(time_sec_float))
    print timec
    status ='0'
    cur.execute("select * from c_auto_9 where qishu = '"+qishu+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `c_auto_9` ( qishu,datetime,ok,ball_1,ball_2,ball_3) values ('"+qishu+"','"+timec+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()                

def Cbet10(html,table): 
    infoencode = chardet.detect(html).get('encoding','gbk')
    html = html.decode(infoencode,'ignore').encode('utf-8')
    title = re.compile(r'kjjg_table\">([\d\D]*?)<\/table>').findall(html)
    titlec = re.compile(r'<tr>([\d\D]*?)<\/tr>').findall(title[0])
    del titlec[0:1]
    del titlec[6:]
    bet = [[0 for col in range(3)] for row in range(6)]
    i =0 
    for ti in titlec:
        qishu = re.compile(r'<td>([\d\D]*?)期</td>').findall(ti)
        bet[i][0] = qishu[0]
        timec = re.compile(r'<td>([\d\D]*?)</td>').findall(ti)
        bet[i][1] = timec[1] 
        bet[i][2] = re.compile(r'\"hm_bg\">([\d\D]*?)<\/div>').findall(ti)
        i+=1
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    status ='0'
    print bet
    for be in bet:
        cur.execute("select * from "+table+" where qishu = '"+be[0]+"'")
        rows = cur.fetchall()
        if not rows: 
            cur.execute("insert into "+table+"  ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5) values ('"+be[0]+"','"+be[1]+"','"+status+"','"+be[2][0]+"','"+be[2][1]+"','"+be[2][2]+"','"+be[2][3]+"','"+be[2][4]+"' )")                                             
            con.commit()
    cur.close()     
    con.close() 


    
        
    

        
operator = {2:Cbet2,3:Cbet3,4:Cbet4,9:Cbet9,10:Cbet2,11:Cbet2,12:Cbet2}

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
  
# if __name__ == '__main__':
def run():
    con = None
    urls = []
    try:
        rows=[
 #                 [2,'http://baidu.lecai.com/lottery/draw/ajax_get_latest_draw_html.php?lottery_type=200'],
#                 [2,'http://api.caipiaokong.com/lottery/?name=cqssc&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3'],#重庆时时彩
#                 [3,'http://api.caipiaokong.com/lottery/?name=bjpks&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3'],#北京赛车PK拾
#                 [4,'http://api.caipiaokong.com/lottery/?name=cqxync&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3'],#重庆快乐十分
#                  [9,'http://cp.swlc.sh.cn/notice/ssl/index.html'] # 上海时时乐
#                 [10,'http://api.caipiaokong.com/lottery/?name=tjssc&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3'],  #天津时时彩
#                 [11,'http://api.caipiaokong.com/lottery/?name=jxssc&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=30'],  #江西时时彩
#                 [12,'http://api.caipiaokong.com/lottery/?name=xjssc&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=30'],  #新疆时彩
                 [11,'http://c.apiplus.net/newly.do?token=6d8caf8c25bc44f5&code=jxssc&format=json&rows='+str(num)],  #江西时时彩
                 [12,'http://c.apiplus.net/newly.do?token=6d8caf8c25bc44f5&code=xjssc&format=json&rows='+str(num)],  #新疆时彩
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
