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
        
# def Cbet1(html): 
#     html = ''.join(html.split())
#     reg=re.compile('<tr>(.*?)<\/tr>',re.I) 
#     html  = re.compile(reg).findall(html)
#     #title = html[1]
#     for title in [html[1],html[2],html[3]]:
#         qishu = re.compile(r'nth-child-1\">(.*?)<\/td>').findall(title)
#         timec = re.compile(r'nth-child-2\">(.*?)<\/td>').findall(title)
#         ball = re.compile(r'ball\">(.*?)<\/em>').findall(title)
#         con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#         cur = con.cursor()
#         d = datetime.datetime.strptime(timec[0],"%Y-%m-%d%H:%M:%S")
#         time_sec_float = time.mktime(d.timetuple())
#         timenow=int(time.time())
#         status ='0'
#         timec[0] = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(time_sec_float))
#         cur.execute("select * from gd_ten_auto where qishu = '20"+qishu[0]+"'")
#         rows = cur.fetchall()
#         if not rows: 
#             cur.execute("insert into `gd_ten_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values ('20"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"' )")                                             
#             con.commit()
#             cur.close()     
#             con.close()
# #         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=gdklsf"
# #         response = urllib2.urlopen(pkurl,timeout=10)
# #         html = response.read()
# #         print html
# #         print 'ccccc'
# #         response.close()        
# #         balance1.balance1(qishu[0])

def Cbet1(html):
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
        qishu1 = qishu[:-3]
        qishu2 = qishu[-2:]
        qishu = qishu1+qishu2
        qishuint = int(qishu)
        ball = title['opencode'].split(',')
        cur.execute("select * from gd_ten_auto where qishu = '"+qishu+"'")
        rows = cur.fetchall()
        if(qishuint>oldnum):
            oldnum = qishuint
            redisarray = [qishu,title['opentime'],ball[0],ball[1],ball[2],ball[3],ball[4],ball[5],ball[6],ball[7]]
        if not rows: 
            cur.execute("insert into `gd_ten_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values ('"+qishu+"','"+title['opentime']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"' )")                                             
            con.commit()
            cur.close()     
            con.close()
            
    if  redisarray:
        print 'ZDREDIS START'
        redisarray = json.dumps(redisarray)
        RedisClent.set('gd_ten_auto_result',redisarray)
        print 'ZDREDIS END'
            
# def Cbet1(html):
#     infoencode = chardet.detect(html).get('encoding','utf-8')
#     html = html.decode(infoencode,'ignore').encode('utf-8')
#     html = json.loads(html)
#     for title in html:
#         print html[title]
#         con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#         cur = con.cursor()
#         ball = html[title]['number'].split(',')
#         cur.execute("select * from gd_ten_auto where qishu = '"+title+"'")
#         rows = cur.fetchall()
#         if not rows: 
#             cur.execute("insert into `gd_ten_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values ('"+title+"','"+html[title]['dateline']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"' )")                                             
#             con.commit()
#             cur.close()     
#             con.close()


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
    cur.execute("select * from cq_ssc_auto where qishu = '"+qishu+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `cq_ssc_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5) values ('"+qishu+"','"+timec+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu+"&cate=cqssc"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#         balance2.balance2(qishu)
    
def Cbet3(html): 
    ball = re.compile(r'{"red":\[([\d\D]*?)],').findall(html)
    ball = re.compile(r'"([\d\D]*?)"').findall(ball[0])
    timec = re.compile(r'latest_draw_time = \'([\d\D]*?)\';').findall(html)
    qishu = re.compile(r'latest_draw_phase = \'([\d\D]*?)\';').findall(html)
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    status ='0'
    cur.execute("select * from bj_10_auto where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `bj_10_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"' )")                                             
        con.commit()
    oldqish = int(qishu[0])-3
    data = re.compile(r'var phaseData = ([\d\D]*?);').findall(html)
    data = json.loads(data[0])
    #data_key=data.keys()
    data_date = data.keys()[0]
    i=0
    status ='0'

    for val in data[data_date]:

        if (oldqish < int(val) and not int(qishu[0]) ==int(val)):
            print val
            ball      = data[data_date][val]['result']['red']
            open_time = data[data_date][val]['open_time']
            
    #         d = datetime.datetime.strptime(open_time,"%Y-%m-%d %H:%M:%S")
    #         time_sec_float = time.mktime(d.timetuple())
    #         timenow=int(time.time())
            status ='0'
            print data_date+':'+val+" open_time:"+open_time
            sql="select * from bj_10_auto where qishu = '"+val+"'"
            #print sql
            try:
                cur.execute(sql)
                rows = cur.fetchall()
                #print rows
            except MySQLdb.Error,e:
                print e
            #print rows[0]
            if not rows:
                insert_sql="insert into `bj_10_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10 ) values ('"+val+"','"+open_time+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"' )"
                print insert_sql
                cur.execute(insert_sql)
                con.commit()                                             
    con.commit()
    cur.close()     
    con.close()
    
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
    cur.execute("select * from cq_ten_auto where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    if not rows: 
        cur.execute("insert into `cq_ten_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=cqklsf"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#         balance4.balance4(qishu[0])
                
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
    qishu[0]=qishu[0].replace(" ", "")
    
    if not ball[0]=='-':   
        print 'ZDREDIS START'
        titleredis=[qishu[0],timec[0],ball[0],ball[1],ball[2]]
        redisarray = json.dumps(titleredis)
        RedisClent.set('fc_3d_auto_result',redisarray)
        print 'ZDREDIS END'       
        cur.execute("select * from fc_3d_auto where qishu = '"+qishu[0]+"'")
        rows = cur.fetchall()
        if not rows: 
            cur.execute("insert into `fc_3d_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"' )")                                               
            con.commit()
            cur.close()     
            con.close()
#             pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=fc3d"
#             response = urllib2.urlopen(pkurl,timeout=10)
#             html = response.read()
#             print html
#             print 'ccccc'
#             response.close()
#             balance5.balance5(qishu[0])
    
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
    timec[0] = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(time_sec_float))
    qishu[0]=qishu[0].replace(" ", "")
    cur.execute("select * from pl_3_auto where qishu = '"+qishu[0]+"'")
    rows = cur.fetchall()
    print 'ZDREDIS START'
    titleredis=[qishu[0],timec[0],ball[0],ball[1],ball[2]]
    redisarray = json.dumps(titleredis)
    RedisClent.set('pl_3_auto_result',redisarray)
    print 'ZDREDIS END' 
    if not rows: 
        cur.execute("insert into `pl_3_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"' )")                                             
        con.commit()
        cur.close()     
        con.close()
#         pkurl="http://mg.pkadmin.com/api/lottery/settle?period="+qishu[0]+"&cate=pls"
#         response = urllib2.urlopen(pkurl,timeout=10)
#         html = response.read()
#         print html
#         print 'ccccc'
#         response.close()
#         balance6.balance6(qishu[0])

# def Cbet7(html):
#     reg = r'no_([\d\D]*?).gif'
#     ball = re.compile(reg).findall(html)
#     reg = r'攪珠日期 : ([\d\D]*?)\('
#     timec = re.compile(reg).findall(html) 
#     timec = timec[0].split('/')
#     timecor =timec[2]+'-'+timec[1]+'-'+timec[0]+' 21:30:00'    
#     reg = r'攪珠期數 : ([\d\D]*?)</td>'
#     qishu = re.compile(reg).findall(html)        
#     qishu =qishu[0].split('/')
#     qishu =timec[2]+qishu[1]
#     con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#     cur = con.cursor()
#     status ='0'
#     cur.execute("select * from liuhecai_auto where nn = '"+str(qishu)+"'")
#     rows = cur.fetchall()
#     zodiacs = [0 for col in range(7)]
#     timenow=int(time.time())
#     timeyear = time.strftime("%Y",  time.localtime(timenow))
#     for key,value in enumerate(ball):
#         zodiacs[key] = zodiac(timeyear,value)
#     if not rows: 
#         cur.execute("insert into `liuhecai_auto` ( nn,nd,na,n1,n2,n3,n4,n5,n6,ok,sx,x1,x2,x3,x4,x5,x6) values ('"+qishu+"','"+timecor+"','"+ball[6]+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+status+"','"+zodiacs[6]+"','"+zodiacs[0]+"','"+zodiacs[1]+"','"+zodiacs[2]+"','"+zodiacs[3]+"','"+zodiacs[4]+"','"+zodiacs[5]+"' )")                                             
#         con.commit()
#         cur.close()     
#         con.close()

# def Cbet8(html):
#     con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#     cur = con.cursor()
#     ######################################当期
#     ball = re.compile(r'{"red":\[([\d\D]*?)],').findall(html)
#     ball = re.compile(r'"([\d\D]*?)"').findall(ball[0])
#     timec = re.compile(r'latest_draw_time = \'([\d\D]*?)\';').findall(html)
#     qishu = re.compile(r'latest_draw_phase = \'([\d\D]*?)\';').findall(html)
#     #con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#     #cur = con.cursor()
# #     d = datetime.datetime.strptime(timec[0],"%Y-%m-%d %H:%M:%S")
# #     time_sec_float = time.mktime(d.timetuple())
# #     timenow=int(time.time())
#     status ='0'
#     cur.execute("select * from bj_8_auto where qishu = '"+qishu[0]+"'")
#     rows = cur.fetchall()
#     if not rows: 
#         cur.execute("insert into `bj_8_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10,ball_11,ball_12,ball_13,ball_14,ball_15,ball_16,ball_17,ball_18,ball_19,ball_20) values ('"+qishu[0]+"','"+timec[0]+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"','"+ball[10]+"','"+ball[11]+"','"+ball[12]+"','"+ball[13]+"','"+ball[14]+"','"+ball[15]+"','"+ball[16]+"','"+ball[17]+"','"+ball[18]+"','"+ball[19]+"' )")                                             
#         con.commit()
# 
# 
#     oldqish = int(qishu[0])-3
#     data = re.compile(r'var phaseData = ([\d\D]*?);').findall(html)
#     data = json.loads(data[0])
#     #data_key=data.keys()
#     data_date = data.keys()[0]
#     i=0
#     status ='0'
# 
#     for val in data[data_date]:
#         if (oldqish < int(val) and not int(qishu[0]) ==int(val)):
#             ball      = data[data_date][val]['result']['red']
#             open_time = data[data_date][val]['open_time']
#             
#     #         d = datetime.datetime.strptime(open_time,"%Y-%m-%d %H:%M:%S")
#     #         time_sec_float = time.mktime(d.timetuple())
#     #         timenow=int(time.time())
#             status ='0'
#             print data_date+':'+val+" open_time:"+open_time
#             sql="select * from bj_8_auto where qishu = '"+val+"'"
#             print sql
#             try:
#                 cur.execute(sql)
#                 rows = cur.fetchone()
#                 #print rows
#             except MySQLdb.Error,e:
#                 print e
#              
#             #print rows[0]
#             if not rows:
#                 insert_sql="insert into `bj_8_auto` ( qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10,ball_11,ball_12,ball_13,ball_14,ball_15,ball_16,ball_17,ball_18,ball_19,ball_20) values ('"+val+"','"+open_time+"','"+status+"','"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"','"+ball[10]+"','"+ball[11]+"','"+ball[12]+"','"+ball[13]+"','"+ball[14]+"','"+ball[15]+"','"+ball[16]+"','"+ball[17]+"','"+ball[18]+"','"+ball[19]+"' )"
#                 print insert_sql
#                 cur.execute(insert_sql) 
#     con.commit()            
#     cur.close()     
#     con.close()    

def Cbet8(html):
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
        cur.execute("select * from bj_8_auto where qishu = '"+qishu+"'")
        rows = cur.fetchall()
        if(qishuint>oldnum):
            oldnum = qishuint
            redisarray = [qishu,title['opentime'],ball[0],ball[1],ball[2],ball[3],ball[4],ball[5],ball[6],ball[7],ball[8],ball[9],ball[10],ball[11],ball[12],ball[13],ball[14],ball[15],ball[16],ball[17],ball[18],ball[19][0:2]]
        if not rows: 
            cur.execute("insert into `bj_8_auto` (  qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10,ball_11,ball_12,ball_13,ball_14,ball_15,ball_16,ball_17,ball_18,ball_19,ball_20) values ('"+qishu+"','"+title['opentime']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"','"+ball[10]+"','"+ball[11]+"','"+ball[12]+"','"+ball[13]+"','"+ball[14]+"','"+ball[15]+"','"+ball[16]+"','"+ball[17]+"','"+ball[18]+"','"+ball[19][0:2]+"' )")                                             
            con.commit()
            cur.close()     
            con.close()
    if  redisarray:
        print 'ZDREDIS START'
        redisarray = json.dumps(redisarray)
        RedisClent.set('bj_8_auto_result',redisarray)
        print 'ZDREDIS END'                    
# def Cbet8(html):
#     infoencode = chardet.detect(html).get('encoding','utf-8')
#     html = html.decode(infoencode,'ignore').encode('utf-8')
#     html = json.loads(html)
#     for title in html:
#         print html[title]
#         con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
#         cur = con.cursor()
#         ball = html[title]['number'].split(',')
#         cur.execute("select * from bj_8_auto where qishu = '"+title+"'")
#         rows = cur.fetchall()
#         if not rows: 
#             cur.execute("insert into `bj_8_auto` (  qishu,datetime,ok,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10,ball_11,ball_12,ball_13,ball_14,ball_15,ball_16,ball_17,ball_18,ball_19,ball_20) values ('"+title+"','"+html[title]['dateline']+"',0,'"+ball[0]+"','"+ball[1]+"','"+ball[2]+"','"+ball[3]+"','"+ball[4]+"','"+ball[5]+"','"+ball[6]+"','"+ball[7]+"','"+ball[8]+"','"+ball[9]+"','"+ball[10]+"','"+ball[11]+"','"+ball[12]+"','"+ball[13]+"','"+ball[14]+"','"+ball[15]+"','"+ball[16]+"','"+ball[17]+"','"+ball[18]+"','"+ball[19]+"' )")                                             
#             con.commit()
#             cur.close()     
#             con.close()
        
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
  
#if __name__ == '__main__':
def run():
    con = None
    urls = []
    try:
        rows=[
                  #[1,'http://pub.icaile.com/gdkl10kjjg.php'],
                 #[1,'http://api.caipiaokong.com/lottery/?name=gdklsf&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3'],#广东快乐十分
                 [1,'http://c.apiplus.net/newly.do?token=6d8caf8c25bc44f5&code=gdklsf&format=json&rows='+str(num)],#广东快乐十分
                 [5,'http://caipiao.163.com/order/3d/'],#福彩3D
                 [6,'http://caipiao.163.com/order/pl3/'],#排列3
#                [7,'http://bet.hkjc.com/marksix/index.aspx?lang=ch'],
                 #[8,'http://baidu.lecai.com/lottery/draw/view/543']
                 #[8,'http://api.caipiaokong.com/lottery/?name=bjklb&format=json&uid=88687&token=89ED44DE68D3F3BECF511F0AC8FC9FDB&num=3']#北京快乐八
                 [8,'http://c.apiplus.net/newly.do?token=6d8caf8c25bc44f5&code=bjkl8&format=json&rows='+str(num)]#北京快乐八
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
