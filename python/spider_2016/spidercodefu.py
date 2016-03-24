'''
Created on 2015-3-24

@author: Administrator
'''
# -*- coding: utf-8 -*-
#encoding=utf-8


import threading
import Queue
import sys
import urllib2
import urllib
import cookielib  
import re
import MySQLdb
import chardet
import datetime
import time
from login_ini import getUid
import globalConfig
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_NAME =  globalConfig.DB_NAME
DB_NAME_P = globalConfig.DB_NAME_P
#
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
    req = urllib2.Request(domain+"/app/member/mem_online.php?uid="+uid,None,headers)
    urllib2.urlopen(req,None,timeout=time)
    req = urllib2.Request(url+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=time) 
    html = response.read()
    reg = r'page\=(.*?)\;'
    response.close()
    page = re.compile(reg).findall(html)
    return page
     
    
def getTitle(Uid,url,uid,page,time=10):
    req = urllib2.Request(domain+"/app/member/mem_online.php?uid="+uid,None,headers)
    urllib2.urlopen(req,None,timeout=time)
    j = 1
    old = url
    while (j <= page):
        _page = j-1
        url = old.replace("page_no=0", "page_no="+'%d' %_page)
        print url+uid
        req = urllib2.Request(url+uid,None,i_headers)
        response = urllib2.urlopen(req,None,timeout=time) 
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
        print j
    else: 
        title=0     
    return title

        
def BRbd1(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    print 'title'
    cur = con.cursor()
    num4 =0
    text4 = "0";
    for ti in title:
        ti[1] = ti[1].split('<br>')
        if len(ti[1]) > 2:
            if not ti[1][2] == "":
                text4 = "1";
            else:
                text4 = "0";
        else:
            text4 = "0";
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        value ="'"+ti[0]+"','"+ti[1][0]+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+ti[7]+"','"+text4+"','"+ti[8]+"','"+ti[9]+"','"+ti[10]+"','"+ti[11]+"','"+ti[12]+"','"+ti[13]+"','"+ti[14]+"','"+ti[15]+"','"+ti[16]+"','"+ti[17]+"','"+ti[18]+"','"+ti[19]+"','"+ti[20]+"','"+ti[21]+"','"+ti[22]+"','"+ti[23]+"','"+ti[24]+"','"+ti[25]+"','"+ti[26]+"','"+ti[27]+"','"+ti[28]+"','"+ti[29]+"','"+ti[30]+"','"+ti[31]+"','"+ti[32]+"','"+ti[33]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"',0,now(),1,"+'%d' %num4
        #print "insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Bd10,Match_Bd20,Match_Bd21,Match_Bd30,Match_Bd31,Match_Bd32,Match_Bd40,Match_Bd41,Match_Bd42,Match_Bd43,Match_Bd00,Match_Bd11,Match_Bd22,Match_Bd33,Match_Bd44,Match_Bdup5,Match_Bdg10,Match_Bdg20,Match_Bdg21,Match_Bdg30,Match_Bdg31,Match_Bdg32,Match_Bdg40,Match_Bdg41,Match_Bdg42,Match_Bdg43,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Bd10,Match_Bd20,Match_Bd21,Match_Bd30,Match_Bd31,Match_Bd32,Match_Bd40,Match_Bd41,Match_Bd42,Match_Bd43,Match_Bd00,Match_Bd11,Match_Bd22,Match_Bd33,Match_Bd44,Match_Bdup5,Match_Bdg10,Match_Bdg20,Match_Bdg21,Match_Bdg30,Match_Bdg31,Match_Bdg32,Match_Bdg40,Match_Bdg41,Match_Bdg42,Match_Bdg43,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")")
        except MySQLdb.Error, e:
            #print e
            #print "Update `bet_match` set Match_Bd10='"+ti[8]+"',Match_Bd20='"+ti[9]+"',Match_Bd21='"+ti[10]+"',Match_Bd30='"+ti[11]+"',Match_Bd31='"+ti[12]+"',Match_Bd32='"+ti[13]+"',Match_Bd40='"+ti[14]+"',Match_Bd41='"+ti[15]+"',Match_Bd42='"+ti[16]+"',Match_Bd43='"+ti[17]+"',Match_Bd00='"+ti[18]+"',Match_Bd11='"+ti[19]+"',Match_Bd22='"+ti[20]+"',Match_Bd33='"+ti[21]+"',Match_Bd44='"+ti[22]+"',Match_Bdup5='"+ti[23]+"',Match_Bdg10='"+ti[24]+"',Match_Bdg20='"+ti[25]+"',Match_Bdg21='"+ti[26]+"',Match_Bdg30='"+ti[27]+"',Match_Bdg31='"+ti[28]+"',Match_Bdg32='"+ti[29]+"',Match_Bdg40='"+ti[30]+"',Match_Bdg41='"+ti[31]+"',Match_Bdg42='"+ti[32]+"',Match_Bdg43='"+ti[33]+"',Match_Type=1,Match_LstTime=now() where Match_ID='"+ti[0]+"' "
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_Bd10='"+ti[8]+"',Match_Bd20='"+ti[9]+"',Match_Bd21='"+ti[10]+"',Match_Bd30='"+ti[11]+"',Match_Bd31='"+ti[12]+"',Match_Bd32='"+ti[13]+"',Match_Bd40='"+ti[14]+"',Match_Bd41='"+ti[15]+"',Match_Bd42='"+ti[16]+"',Match_Bd43='"+ti[17]+"',Match_Bd00='"+ti[18]+"',Match_Bd11='"+ti[19]+"',Match_Bd22='"+ti[20]+"',Match_Bd33='"+ti[21]+"',Match_Bd44='"+ti[22]+"',Match_Bdup5='"+ti[23]+"',Match_Bdg10='"+ti[24]+"',Match_Bdg20='"+ti[25]+"',Match_Bdg21='"+ti[26]+"',Match_Bdg30='"+ti[27]+"',Match_Bdg31='"+ti[28]+"',Match_Bdg32='"+ti[29]+"',Match_Bdg40='"+ti[30]+"',Match_Bdg41='"+ti[31]+"',Match_Bdg42='"+ti[32]+"',Match_Bdg43='"+ti[33]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' "
            cur.execute(sql)
            num4+=1                                                  
    con.commit()
    cur.close()     
    con.close() 
    
def BRZRQ2(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0";
    for ti in title:
        ti[1] = ti[1].split('<br>')
        if len(ti[1]) > 2:
            if not ti[1][2] == "":
                text4 = "1"
            else:
                text4 = "0"
        else:
            text4 = "0"
        if ti[15] =='': ti[15] ='0'  
        if ti[16] =='': ti[16] ='0'  
        if ti[14] =='': ti[14] ='0'  
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        value ="'"+ti[0]+"','"+ti[1][0]+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+ti[7]+"','"+text4+"','"+ti[10]+"','"+ti[11]+"','"+ti[12]+"','"+ti[13]+"','"+ti[14]+"','"+ti[15]+"','"+ti[16]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"',0,now(),1,"+'%d' %num4
        print "insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Total01Pl,Match_Total23Pl,Match_Total46Pl,Match_Total7upPl,Match_BzM,Match_BzG,Match_BzH,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Total01Pl,Match_Total23Pl,Match_Total46Pl,Match_Total7upPl,Match_BzM,Match_BzG,Match_BzH,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")")
        except MySQLdb.Error, e:
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_Total01Pl='"+ti[10]+"',Match_Total23Pl='"+ti[11]+"',Match_Total46Pl='"+ti[12]+"',Match_Total7upPl='"+ti[13]+"',Match_BzM='"+ti[14]+"',Match_BzG='"+ti[15]+"',Match_BzH='"+ti[16]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' AND Match_StopUpdatebd =0"
            print sql
            cur.execute(sql)
            num4+=1;                                                
    con.commit()
    cur.close()     
    con.close()
    
def BRBQC3(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0";
    for ti in title:
        ti[1] = ti[1].split('<br>')
        if len(ti[1]) > 2:
            if not ti[1][2] == "":
                text4 = "1";
            else:
                text4 = "0";
        else:
            text4 = "0";
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        value ="'"+ti[0]+"','"+ti[1][0]+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+ti[7]+"','"+text4+"','"+ti[8]+"','"+ti[9]+"','"+ti[10]+"','"+ti[11]+"','"+ti[12]+"','"+ti[13]+"','"+ti[14]+"','"+ti[15]+"','"+ti[16]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"',0,now(),1,"+'%d' %num4
        print "insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_BqMM,Match_BqMH,Match_BqMG,Match_BqHM,Match_BqHH,Match_BqHG,Match_BqGM,Match_BqGH,Match_BqGG,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_BqMM,Match_BqMH,Match_BqMG,Match_BqHM,Match_BqHH,Match_BqHG,Match_BqGM,Match_BqGH,Match_BqGG,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")")
        except MySQLdb.Error, e:
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_BqMM='"+ti[8]+"',Match_BqMH='"+ti[9]+"',Match_BqMG='"+ti[10]+"',Match_BqHM='"+ti[11]+"',Match_BqHH='"+ti[12]+"',Match_BqHG='"+ti[13]+"',Match_BqGM='"+ti[14]+"',Match_BqGH='"+ti[15]+"',Match_BqGG='"+ti[16]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' and Match_StopUpdatet =0 "
            print sql
            cur.execute(sql)
            num4+=1;                                                 
    con.commit()
    cur.close()     
    con.close()
    
def FUJR4(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0"
    print len(title)
    for ti in title:
        ti[1] = ti[1].split('<br>')
        print ti[1]
        if (len(ti[1]) > 2):
            if not (ti[1][2] == ""):
                text4 = "1"
            else:
                text4 = "0"
        else:
            text4 = "0"
         
        if ti[25] =='':ti[25] ="0" 
        if ti[26] =='':ti[26] ='0'  
        if ti[29] =='':ti[29] ='0'  
        if ti[30] =='':ti[30] ='0'  
        if ti[34] =='': ti[34] ='0'  
        if ti[15] =='': ti[15] ='0'  
        if ti[16] =='': ti[16] ='0'  
        if ti[17] =='': ti[17] ='0'  
        if ti[31] =='': ti[31] ='0'  
        if ti[32] =='': ti[32] ='0'  
        if ti[33] =='': ti[33] ='0'  
        if ti[20] =='': ti[20] ='0' 
        if ti[21] =='': ti[21] ='0' 
        if ti[9] =='': ti[9] ='0' 
        if ti[10] =='': ti[10] ='0' 
        ti[27] = ti[27].replace('O','') 
        ti[11] = ti[11].replace('O','') 
        ti[11] = ''.join(ti[11].split())
        ti[10] = ''.join(ti[10].split())
        ti[24] = ''.join(ti[24].split()) 
        ti[8] = ''.join(ti[8].split())  
        ti[27] = ''.join(ti[27].split())           
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        value ="'"+ti[0]+"','"+ti[22]+"','"+ti[1][0]+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+text4+"',0,'"+ti[7]+"','"+ti[9]+"','"+ti[10]+"','"+ti[8]+"','"+ti[15]+"','"+ti[16]+"','"+ti[17]+"','"+ti[11]+"','"+ti[14]+"','"+ti[13]+"','"+ti[20]+"','"+ti[21]+"','"+ti[23]+"','"+ti[24]+"','"+ti[25]+"','"+ti[26]+"','"+ti[30]+"','"+ti[29]+"','"+ti[27]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"','"+ti[31]+"','"+ti[32]+"','"+ti[33]+"','"+ti[34]+"','"+ti[1][0]+" "+ti[1][1]+"',now(),1,"+'%d' %num4
        #print "insert into bet_match(Match_ID,Match_HalfId,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_BzM,Match_BzG,Match_BzH,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_Hr_ShowType,Match_BRpk,Match_BHo,Match_BAo,Match_Bdpl,Match_Bxpl,Match_BDxpk,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Bmdy,Match_Bgdy,Match_Bhdy,Match_TypePlay,Match_MatchTime,Match_LstTime,iPage,iSn) values ("+value+")"
        #print "insert into bet_match(Match_ID,Match_HalfId,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_BzM,Match_BzG,Match_BzH,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_Hr_ShowType,Match_BRpk,Match_BHo,Match_BAo,Match_Bdpl,Match_Bxpl,Match_BDxpk,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Bmdy,Match_Bgdy,Match_Bhdy,Match_TypePlay,Match_MatchTime,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into bet_match(Match_ID,Match_HalfId,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_BzM,Match_BzG,Match_BzH,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_Hr_ShowType,Match_BRpk,Match_BHo,Match_BAo,Match_Bdpl,Match_Bxpl,Match_BDxpk,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Bmdy,Match_Bgdy,Match_Bhdy,Match_TypePlay,Match_MatchTime,Match_LstTime,iPage,iSn) values ("+value+")")
        except MySQLdb.Error, e:
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_HalfId='"+ti[22]+"',Match_RGG='"+ti[8]+"',Match_BzM='"+ti[15]+"',Match_BzG='"+ti[16]+"',Match_BzH='"+ti[17]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_DsDpl='"+ti[20]+"',Match_DsSpl='"+ti[21]+"',Match_Hr_ShowType='"+ti[23]+"',Match_BRpk='"+ti[24]+"',Match_BHo='"+ti[25]+"',Match_BAo='"+ti[26]+"',Match_Bdpl='"+ti[30]+"',Match_Bxpl='"+ti[29]+"',Match_BDxpk='"+ti[27]+"',Match_Bmdy='"+ti[31]+"',Match_Bgdy='"+ti[32]+"',Match_Bhdy='"+ti[33]+"',Match_TypePlay='"+ti[34]+"',Match_Type=1,Match_LstTime=now() where Match_ID='"+ti[0]+"' and Match_StopUpdatebq=0"                                              
            #print sql
            cur.execute(sql) 
            num4+=1;
    
    con.commit()
    cur.close()     
    con.close() 
    
def BRbd5(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    print 'title'
    cur = con.cursor()
    num4 =0
    text4 = "0";
    for ti in title:
        ti[1] = ti[1].split('<br>')
        if len(ti[1]) > 2:
            if not ti[1][2] == "":
                text4 = "1";
            else:
                text4 = "0";
        else:
            text4 = "0";
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        Match_Date =ti[1][0]
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
#         elif(('p' in ti[1][1]) and ('12' in ti[1][1])):
#             uptime = 12*60*60
#             Match_Date = ti[1][0][0:2]
#             Match_Date = int(Match_Date)
#             Match_Date = Match_Date+1
#             Match_Date = ti[1][0][:-2]+str(Match_Date)    
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        ti[2] = ''.join(ti[2].split())
        ti[5] = ''.join(ti[5].split())
        ti[6] = ''.join(ti[6].split())
        value ="'"+ti[0]+"','"+Match_Date+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+ti[7]+"','"+text4+"','"+ti[8]+"','"+ti[9]+"','"+ti[10]+"','"+ti[11]+"','"+ti[12]+"','"+ti[13]+"','"+ti[14]+"','"+ti[15]+"','"+ti[16]+"','"+ti[17]+"','"+ti[18]+"','"+ti[19]+"','"+ti[20]+"','"+ti[21]+"','"+ti[22]+"','"+ti[23]+"','"+ti[24]+"','"+ti[25]+"','"+ti[26]+"','"+ti[27]+"','"+ti[28]+"','"+ti[29]+"','"+ti[30]+"','"+ti[31]+"','"+ti[32]+"','"+ti[33]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"',0,now(),1,"+'%d' %num4
        print "insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Bd10,Match_Bd20,Match_Bd21,Match_Bd30,Match_Bd31,Match_Bd32,Match_Bd40,Match_Bd41,Match_Bd42,Match_Bd43,Match_Bd00,Match_Bd11,Match_Bd22,Match_Bd33,Match_Bd44,Match_Bdup5,Match_Bdg10,Match_Bdg20,Match_Bdg21,Match_Bdg30,Match_Bdg31,Match_Bdg32,Match_Bdg40,Match_Bdg41,Match_Bdg42,Match_Bdg43,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Bd10,Match_Bd20,Match_Bd21,Match_Bd30,Match_Bd31,Match_Bd32,Match_Bd40,Match_Bd41,Match_Bd42,Match_Bd43,Match_Bd00,Match_Bd11,Match_Bd22,Match_Bd33,Match_Bd44,Match_Bdup5,Match_Bdg10,Match_Bdg20,Match_Bdg21,Match_Bdg30,Match_Bdg31,Match_Bdg32,Match_Bdg40,Match_Bdg41,Match_Bdg42,Match_Bdg43,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")")
            con.commit()
        except MySQLdb.Error, e:
            print e
            print "Update `bet_match` set Match_CoverDate='"+text5+"',Match_Bd10='"+ti[8]+"',Match_Bd20='"+ti[9]+"',Match_Bd21='"+ti[10]+"',Match_Bd30='"+ti[11]+"',Match_Bd31='"+ti[12]+"',Match_Bd32='"+ti[13]+"',Match_Bd40='"+ti[14]+"',Match_Bd41='"+ti[15]+"',Match_Bd42='"+ti[16]+"',Match_Bd43='"+ti[17]+"',Match_Bd00='"+ti[18]+"',Match_Bd11='"+ti[19]+"',Match_Bd22='"+ti[20]+"',Match_Bd33='"+ti[21]+"',Match_Bd44='"+ti[22]+"',Match_Bdup5='"+ti[23]+"',Match_Bdg10='"+ti[24]+"',Match_Bdg20='"+ti[25]+"',Match_Bdg21='"+ti[26]+"',Match_Bdg30='"+ti[27]+"',Match_Bdg31='"+ti[28]+"',Match_Bdg32='"+ti[29]+"',Match_Bdg40='"+ti[30]+"',Match_Bdg41='"+ti[31]+"',Match_Bdg42='"+ti[32]+"',Match_Bdg43='"+ti[33]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' "
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_Bd10='"+ti[8]+"',Match_Bd20='"+ti[9]+"',Match_ShowType='"+ti[7]+"',Match_Bd21='"+ti[10]+"',Match_Bd30='"+ti[11]+"',Match_Bd31='"+ti[12]+"',Match_Bd32='"+ti[13]+"',Match_Bd40='"+ti[14]+"',Match_Bd41='"+ti[15]+"',Match_Bd42='"+ti[16]+"',Match_Bd43='"+ti[17]+"',Match_Bd00='"+ti[18]+"',Match_Bd11='"+ti[19]+"',Match_Bd22='"+ti[20]+"',Match_Bd33='"+ti[21]+"',Match_Bd44='"+ti[22]+"',Match_Bdup5='"+ti[23]+"',Match_Bdg10='"+ti[24]+"',Match_Bdg20='"+ti[25]+"',Match_Bdg21='"+ti[26]+"',Match_Bdg30='"+ti[27]+"',Match_Bdg31='"+ti[28]+"',Match_Bdg32='"+ti[29]+"',Match_Bdg40='"+ti[30]+"',Match_Bdg41='"+ti[31]+"',Match_Bdg42='"+ti[32]+"',Match_Bdg43='"+ti[33]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' "
            cur.execute(sql)
            con.commit()
            num4+=1                                                  
    con.commit()
    cur.close()     
    con.close() 

def BRZRQ6(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0";
    for ti in title:
        ti[1] = ti[1].split('<br>')
        if len(ti[1]) > 2:
            if not ti[1][2] == "":
                text4 = "1"
            else:
                text4 = "0"
        else:
            text4 = "0"
        if ti[15] =='': ti[15] ='0'  
        if ti[16] =='': ti[16] ='0'  
        if ti[14] =='': ti[14] ='0'  
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        Match_Date =ti[1][0]
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
#         elif(('p' in ti[1][1]) and ('12' in ti[1][1])):
#             uptime = 12*60*60
#             Match_Date = ti[1][0][0:2]
#             Match_Date = int(Match_Date)
#             Match_Date = Match_Date+1
#             Match_Date = ti[1][0][:-2]+str(Match_Date)
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        ti[2] = ''.join(ti[2].split())
        ti[5] = ''.join(ti[5].split())
        ti[6] = ''.join(ti[6].split())
        value ="'"+ti[0]+"','"+Match_Date+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+ti[7]+"','"+text4+"','"+ti[10]+"','"+ti[11]+"','"+ti[12]+"','"+ti[13]+"','"+ti[14]+"','"+ti[15]+"','"+ti[16]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"',0,now(),1,"+'%d' %num4
        print "insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Total01Pl,Match_Total23Pl,Match_Total46Pl,Match_Total7upPl,Match_BzM,Match_BzG,Match_BzH,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_Total01Pl,Match_Total23Pl,Match_Total46Pl,Match_Total7upPl,Match_BzM,Match_BzG,Match_BzH,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")")
            con.commit()
        except MySQLdb.Error, e:
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_Total01Pl='"+ti[10]+"',Match_Total23Pl='"+ti[11]+"',Match_ShowType='"+ti[7]+"',Match_Total46Pl='"+ti[12]+"',Match_Total7upPl='"+ti[13]+"',Match_BzM='"+ti[14]+"',Match_BzG='"+ti[15]+"',Match_BzH='"+ti[16]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' and Match_StopUpdatebd =0"
            print sql
            cur.execute(sql)
            con.commit()
            num4+=1;                                                
    con.commit()
    cur.close()     
    con.close()

def BRBQC7(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0";
    for ti in title:
        ti[1] = ti[1].split('<br>')
        if len(ti[1]) > 2:
            if not ti[1][2] == "":
                text4 = "1";
            else:
                text4 = "0";
        else:
            text4 = "0";
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        Match_Date =ti[1][0]
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
#         elif(('p' in ti[1][1]) and ('12' in ti[1][1])):
#             uptime = 12*60*60
#             Match_Date = ti[1][0][0:2]
#             Match_Date = int(Match_Date)
#             Match_Date = Match_Date+1
#             Match_Date = ti[1][0][:-2]+str(Match_Date)
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        ti[2] = ''.join(ti[2].split())
        ti[5] = ''.join(ti[5].split())
        ti[6] = ''.join(ti[6].split())
        value ="'"+ti[0]+"','"+Match_Date+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+ti[7]+"','"+text4+"','"+ti[8]+"','"+ti[9]+"','"+ti[10]+"','"+ti[11]+"','"+ti[12]+"','"+ti[13]+"','"+ti[14]+"','"+ti[15]+"','"+ti[16]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"',0,now(),1,"+'%d' %num4
        print "insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_BqMM,Match_BqMH,Match_BqMG,Match_BqHM,Match_BqHH,Match_BqHG,Match_BqGM,Match_BqGH,Match_BqGG,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into `bet_match` ( Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_ShowType,Match_IsLose,Match_BqMM,Match_BqMH,Match_BqMG,Match_BqHM,Match_BqHH,Match_BqHG,Match_BqGM,Match_BqGH,Match_BqGG,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Type,Match_LstTime,iPage,iSn) values ("+value+")")
            con.commit()
        except MySQLdb.Error, e:
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_BqMM='"+ti[8]+"',Match_ShowType='"+ti[7]+"',Match_BqMH='"+ti[9]+"',Match_BqMG='"+ti[10]+"',Match_BqHM='"+ti[11]+"',Match_BqHH='"+ti[12]+"',Match_BqHG='"+ti[13]+"',Match_BqGM='"+ti[14]+"',Match_BqGH='"+ti[15]+"',Match_BqGG='"+ti[16]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' and Match_StopUpdatet =0"
            print sql
            cur.execute(sql)
            con.commit()
            num4+=1;                                                 
    con.commit()
    cur.close()     
    con.close()

def FUJR8(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0"
    print len(title)
    for ti in title:
        ti[1] = ti[1].split('<br>')
        print ti[1]
        if (len(ti[1]) > 2):
            if not (ti[1][2] == ""):
                text4 = "1"
            else:
                text4 = "0"
        else:
            text4 = "0"
         
        if ti[25] =='':ti[25] ="0" 
        if ti[26] =='':ti[26] ='0'  
        if ti[29] =='':ti[29] ='0'  
        if ti[30] =='':ti[30] ='0'  
        if ti[34] =='': ti[34] ='0'  
        if ti[15] =='': ti[15] ='0'  
        if ti[16] =='': ti[16] ='0'  
        if ti[17] =='': ti[17] ='0'  
        if ti[31] =='': ti[31] ='0'  
        if ti[32] =='': ti[32] ='0'  
        if ti[33] =='': ti[33] ='0'  
        if ti[20] =='': ti[20] ='0' 
        if ti[21] =='': ti[21] ='0' 
        if ti[9] =='': ti[9] ='0' 
        if ti[10] =='': ti[10] ='0'         
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        Match_Date =ti[1][0]
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
#         elif(('p' in ti[1][1]) and ('12' in ti[1][1])):
#             uptime = 12*60*60
#             Match_Date = ti[1][0][0:2]
#             Match_Date = int(Match_Date)
#             Match_Date = Match_Date+1
#             Match_Date = ti[1][0][:-2]+str(Match_Date)
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        ti[27] = ti[27].replace('O','') 
        ti[11] = ti[11].replace('O','') 
        ti[11] = ''.join(ti[11].split())
        ti[10] = ''.join(ti[10].split())
        ti[8] = ''.join(ti[8].split()) 
        ti[24] = ''.join(ti[24].split())
        ti[27] = ''.join(ti[27].split())
        ti[2] = ''.join(ti[2].split())
        ti[5] = ''.join(ti[5].split())
        ti[6] = ''.join(ti[6].split())
        value ="'"+ti[0]+"','"+ti[22]+"','"+Match_Date+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+text4+"',0,'"+ti[7]+"','"+ti[9]+"','"+ti[10]+"','"+ti[8]+"','"+ti[15]+"','"+ti[16]+"','"+ti[17]+"','"+ti[11]+"','"+ti[14]+"','"+ti[13]+"','"+ti[20]+"','"+ti[21]+"','"+ti[23]+"','"+ti[24]+"','"+ti[25]+"','"+ti[26]+"','"+ti[30]+"','"+ti[29]+"','"+ti[27]+"','"+text5+"','"+ti[3]+"','"+ti[4]+"','"+ti[31]+"','"+ti[32]+"','"+ti[33]+"','"+ti[34]+"','"+ti[1][0]+" "+ti[1][1]+"',now(),1,"+'%d' %num4
        print "insert into bet_match(Match_ID,Match_HalfId,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_BzM,Match_BzG,Match_BzH,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_Hr_ShowType,Match_BRpk,Match_BHo,Match_BAo,Match_Bdpl,Match_Bxpl,Match_BDxpk,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Bmdy,Match_Bgdy,Match_Bhdy,Match_TypePlay,Match_MatchTime,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into bet_match(Match_ID,Match_HalfId,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_BzM,Match_BzG,Match_BzH,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_Hr_ShowType,Match_BRpk,Match_BHo,Match_BAo,Match_Bdpl,Match_Bxpl,Match_BDxpk,Match_CoverDate,Match_MasterID,Match_GuestID,Match_Bmdy,Match_Bgdy,Match_Bhdy,Match_TypePlay,Match_MatchTime,Match_LstTime,iPage,iSn) values ("+value+")")
            con.commit()
        except MySQLdb.Error, e:
            sql = "Update `bet_match` set Match_CoverDate='"+text5+"',Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_ShowType='"+ti[7]+"',Match_RGG='"+ti[8]+"',Match_BzM='"+ti[15]+"',Match_BzG='"+ti[16]+"',Match_BzH='"+ti[17]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_DsDpl='"+ti[20]+"',Match_DsSpl='"+ti[21]+"',Match_Hr_ShowType='"+ti[23]+"',Match_BRpk='"+ti[24]+"',Match_BHo='"+ti[25]+"',Match_BAo='"+ti[26]+"',Match_Bdpl='"+ti[30]+"',Match_Bxpl='"+ti[29]+"',Match_BDxpk='"+ti[27]+"',Match_Bmdy='"+ti[31]+"',Match_Bgdy='"+ti[32]+"',Match_Bhdy='"+ti[33]+"',Match_TypePlay='"+ti[34]+"',Match_Type=0,Match_LstTime=now() where Match_ID='"+ti[0]+"' and Match_StopUpdatebq=0"                                              
            print sql
            cur.execute(sql) 
            con.commit()
            num4+=1;                                                 
    con.commit()
    cur.close()     
    con.close()
               
def BKRMAIN9(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0"
    print len(title)
    for ti in title:
        ti[1] = ti[1].split('<br>')
        print ti[1]
        if (len(ti[1]) > 2):
            if not (ti[1][2] == ""):
                text4 = "1"
            else:
                text4 = "0"
        else:
            text4 = "0"
        if ti[20] =='': ti[20] ='0'    
        if ti[21] =='': ti[21] ='0'  
        if ti[14] =='': ti[14] ='0' 
        if ti[13] =='': ti[13] ='0'    
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        ti[11] = ti[11].replace('O','') 
        ti[11] = ''.join(ti[11].split())
        ti[10] = ''.join(ti[10].split())
        ti[8] = ''.join(ti[8].split())
        value ="'"+ti[0]+"','"+ti[1][0]+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+text4+"',1,'"+ti[7]+"','"+ti[9]+"','"+ti[10]+"','"+ti[8]+"','"+ti[11]+"','"+ti[14]+"','"+ti[13]+"','"+ti[20]+"','"+ti[21]+"','"+text5+"','"+ti[1][0]+" "+ti[1][1]+"','"+ti[3]+"','"+ti[4]+"',now(),1,"+'%d' %num4
        print "insert into lq_match(Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_CoverDate,Match_MatchTime,Match_MasterID,Match_GuestID,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into lq_match(Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_CoverDate,Match_MatchTime,Match_MasterID,Match_GuestID,Match_LstTime,iPage,iSn) values ("+value+")")
        except MySQLdb.Error, e:
            sql = "Update `lq_match` set Match_CoverDate='"+text5+"',Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_RGG='"+ti[8]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_DsDpl='"+ti[20]+"',Match_DsSpl='"+ti[21]+"',Match_LstTime=now() where Match_ID='"+ti[0]+"' "                                              
            print sql
            cur.execute(sql) 
            num4+=1;                                                 
    con.commit()
    cur.close()     
    con.close()

def BKRMAIN11(title): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0"
    print len(title)
    for ti in title:
        ti[1] = ti[1].split('<br>')
        print ti[1]
        if (len(ti[1]) > 2):
            if not (ti[1][2] == ""):
                text4 = "1"
            else:
                text4 = "0"
        else:
            text4 = "0"
        if ti[20] =='': ti[20] ='0'    
        if ti[21] =='': ti[21] ='0'  
        if ti[14] =='': ti[14] ='0' 
        if ti[13] =='': ti[13] ='0'    
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        ti[11] = ti[11].replace('O','') 
        ti[11] = ''.join(ti[11].split())
        ti[10] = ''.join(ti[10].split())
        ti[8] = ''.join(ti[8].split())
        value ="'"+ti[0]+"','"+ti[1][0]+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+text4+"',0,'"+ti[7]+"','"+ti[9]+"','"+ti[10]+"','"+ti[8]+"','"+ti[11]+"','"+ti[14]+"','"+ti[13]+"','"+ti[20]+"','"+ti[21]+"','"+text5+"','"+ti[1][0]+" "+ti[1][1]+"','"+ti[3]+"','"+ti[4]+"',now(),1,"+'%d' %num4
        print "insert into lq_match(Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_CoverDate,Match_MatchTime,Match_MasterID,Match_GuestID,Match_LstTime,iPage,iSn) values ("+value+")"
        try:
            cur.execute("insert into lq_match(Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_IsLose,Match_Type,Match_ShowType,Match_Ho,Match_Ao,Match_RGG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_CoverDate,Match_MatchTime,Match_MasterID,Match_GuestID,Match_LstTime,iPage,iSn) values ("+value+")")
        except MySQLdb.Error, e:
            sql = "Update `lq_match` set Match_CoverDate='"+text5+"',Match_Type=0,Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_RGG='"+ti[8]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_DsDpl='"+ti[20]+"',Match_DsSpl='"+ti[21]+"',Match_LstTime=now() where Match_ID='"+ti[0]+"' "                                              
            print sql
            cur.execute(sql) 
            num4+=1;                                                 
    con.commit()
    cur.close()     
    con.close()
    
def TNBRO13(title,table): 
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    num4 =0
    text4 = "0"
    print len(title)
    for ti in title:
        ti[1] = ti[1].split('<br>')
        ti[12] = ti[12].replace("U", "")
        ti[11] = ti[11].replace("O", "")
        ti[5] = ti[5].replace("<fontcolor=gray>", "")
        ti[6] = ti[6].replace("<fontcolor=gray>", "")
        ti[5] = ti[5].replace("<font color=gray>", "")
        ti[5] = ti[5].replace("</font>", "")
        ti[6] = ti[6].replace("<font color=gray>", "")
        ti[6] = ti[6].replace("</font>", "")
        print ti[1]
        if (len(ti[1]) > 2):
            if not (ti[1][2] == ""):
                text4 = "1"
            else:
                text4 = "0"
        else:
            text4 = "0"
        if ti[9] =='': ti[9] ='0' 
        if ti[10] =='': ti[10] ='0' 
        if ti[14] =='': ti[14] ='0'  
        if ti[15] =='': ti[15] ='0' 
        if ti[16] =='': ti[16] ='0' 
        if ti[20] =='': ti[20] ='0'    
        if ti[21] =='': ti[21] ='0'     
        text5 = ti[1][0]+' '+ti[1][1]+"'"
        uptime =0
        if(('p' in ti[1][1]) and (not '12' in ti[1][1])):
            uptime = 12*60*60
        text5 = text5[:-2]
        text5 = text5+':00'
        year = time.strftime('%Y',time.localtime(time.time()))
        text5=year+'-'+text5
        text5 = datetime.datetime.strptime(text5,'%Y-%m-%d %H:%M:%S')
        text5 = time.mktime(text5.timetuple())
        text5 = text5+uptime
        text5 = time.strftime("%Y-%m-%d %H:%M:%S",  time.localtime(text5))
        ti[11] = ti[11].replace('O','')
        ti[11] = ''.join(ti[11].split()) 
        ti[10] = ''.join(ti[10].split())
        ti[8] = ''.join(ti[8].split()) 
        value ="'"+ti[0]+"','"+ti[1][0]+"','"+ti[1][1]+"','"+ti[2]+"','"+ti[5]+"','"+ti[6]+"','"+ti[3]+"','"+ti[4]+"','"+text4+"',0,'"+ti[9]+"','"+ti[10]+"','"+ti[8]+"','"+ti[15]+"','"+ti[16]+"','"+ti[11]+"','"+ti[14]+"','"+ti[13]+"','"+ti[20]+"','"+ti[21]+"','"+ti[7]+"','"+text5+"','"+ti[1][0]+" "+ti[1][1]+"'"
        print "insert into "+table+"(Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_Masterid,Match_Guestid,Match_IsLose,Match_Type,Match_Ho,Match_Ao,Match_RGG,Match_BzM,Match_BzG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_ShowType,Match_CoverDate,Match_MatchTime) values ("+value+")"
        try:
            cur.execute("insert into "+table+"(Match_ID,Match_Date,Match_Time,Match_Name,Match_Master,Match_Guest,Match_Masterid,Match_Guestid,Match_IsLose,Match_Type,Match_Ho,Match_Ao,Match_RGG,Match_BzM,Match_BzG,Match_DxGG,Match_DxDpl,Match_DxXpl,Match_DsDpl,Match_DsSpl,Match_ShowType,Match_MatchTime) values ("+value+")")
        except MySQLdb.Error, e:
            sql = "Update `"+table+"` set  Match_Type=0,Match_Date='"+ti[1][0]+"',Match_Time='"+ti[1][1]+"',Match_Name='"+ti[2]+"',Match_Master='"+ti[5]+"',Match_Guest='"+ti[6]+"',Match_Masterid='"+ti[3]+"',Match_Guestid='"+ti[4]+"',Match_IsLose='"+text4+"',Match_Ho='"+ti[9]+"',Match_Ao='"+ti[10]+"',Match_RGG='"+ti[8]+"',Match_BzM='"+ti[15]+"',Match_BzG='"+ti[16]+"',Match_DxGG='"+ti[11]+"',Match_DxDpl='"+ti[14]+"',Match_DxXpl='"+ti[13]+"',Match_DsDpl='"+ti[20]+"',Match_DsSpl='"+ti[21]+"',Match_ShowType='"+ti[7]+"' ,Match_CoverDate='"+text5+"' where Match_ID='"+ti[0]+"' "                                                                     
            print sql
            cur.execute(sql) 
            num4+=1;                                                 
    con.commit()
    cur.close()     
    con.close()    

                    
operator = {1:FUJR4,2:BRbd1,3:BRZRQ2,4:BRBQC3,5:FUJR8,6:BRbd5,7:BRZRQ6,8:BRBQC7,9:BKRMAIN9,11:BKRMAIN11,13:TNBRO13,14:TNBRO13,17:TNBRO13}

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
    con = None
    urls = []
    try:
        rows=[
             [5,'/app/member/FT_future/body_var.php?rtype=r&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&g_date=&uid='],
             [6,'/app/member/FT_future/body_var.php?rtype=pd&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&g_date=&uid='],
             [7,'/app/member/FT_future/body_var.php?rtype=t&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&g_date=&uid='],
             [8,'/app/member/FT_future/body_var.php?rtype=f&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&g_date=&uid='],
             [13,'/app/member/TN_future/body_var.php?rtype=r_all&langx=zh-cn&mtype=3&page_no=0&league_id=&hot_game=&g_date=ALL&uid='],
             [14,'/app/member/VB_future/body_var.php?rtype=r_all&langx=zh-cn&mtype=3&page_no=0&league_id=&hot_game=&g_date=ALL&uid='],
             [17,'/app/member/BS_future/body_var.php?rtype=r&langx=zh-cn&mtype=3&page_no=0&league_id=&g_date=ALL&uid=']
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
