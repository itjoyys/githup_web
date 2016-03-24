#encoding=utf-8
#!/usr/bin/python
#import threading
#import Queue
#import sys
import urllib2
import urllib
#import cookielib  
import re
import MySQLdb
#import chardet
#import datetime
#import time
import globalConfig
#
#
              
  
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
username1 =globalConfig.username1
username2 =globalConfig.username2
account =[username1,username2]  
DB_NAME = globalConfig.DB_NAME
DB_NAME_P = globalConfig.DB_NAME_P    
headers = {"User-Agent": "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5",
             "Referer": globalConfig.dominAll}
 
def logindate (n,langx ,username,passwd,hosturl):
    postData = {'langx' : langx,  
        'username' : username,  
        'passwd' : passwd
        }
    postData = urllib.urlencode(postData)
    hosturlda  = globalConfig.dominAll+'/app/member/new_login.php'
    print hosturlda
    print postData
    print headers
    req = urllib2.Request(hosturlda, postData, headers) 
     
    html = urllib2.urlopen(req).read()
    print n-1
    print username
    print passwd
    print html

    if not (('密码' in html)  or ('被停用' in html) ) or n==-1: 
        htmlc =html.split('|')
        uid = htmlc[3]
        print globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid
        try:
            response = urllib2.urlopen(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,timeout=10)
        except Exception as e:
            print e
            response = urllib2.urlopen(globalConfig.dominAll+"/app/member/FT_index.php?mtype=4&langx=zh-cn&news_mem=Y&uid="+uid,timeout=10)
        domian = response.read()
        print domian
        url = re.compile(r'action=\'(.*?)\'').findall(domian) 
        if(url):
            con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
            cur = con.cursor()
            sql = "update  `spideruser` set `hgweb`='"+url[0]+"'  where id=1"
            print sql
            try:
                cur.execute(sql) 
            except MySQLdb.Error, e:
                print e                                               
            con.commit()
            cur.close()     
            con.close()
        data = {'html':html,'username':username,'passwd':passwd}
        return data
    else:
        n=n-1
        return logindate(n,langx,account[n][0],account[n][1],hosturl)

            
def HgLoginInfo():#从数据库cyj_private获取HG帐号信息
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor()
    sql = "select `hgweb`,`hguser`,`hgpass`,`COOKIE` from `spideruser` where id=1"
    print sql
    try:
        cur.execute(sql)
        data = cur.fetchone()
        hgweb=data[0]
        hguser=data[1]
        hgpass=data[2]
        COOKIE=data[3] 
        print hgweb,hguser,hgpass,COOKIE
    except MySQLdb.Error, e:
        print e                                               
    con.commit()
    cur.close()     
    con.close()
    return data
def HgUpDate(COOKIE,username,passwd):#cyj_private更新HG帐号TOKEN
    if COOKIE:
        con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
        cur = con.cursor()
        sql = "update  `spideruser` set `COOKIE`='"+COOKIE+"' , `hguser`='"+username+"' , `hgpass`='"+passwd+"' where id=1"
        print sql
        try:
            cur.execute(sql) 
        except MySQLdb.Error, e:
            print e                                               
        con.commit()
        cur.close()     
        con.close()
def getUid(GetUid=0):
    ###############################
    HgData=HgLoginInfo()     
    ###############################
    langx='zh-cn'
    username=HgData[1]
    passwd=HgData[2]
    hosturl=HgData[0]
    uid = HgData[3]
    ###################################
    try:
        headers = {'User-Agent' : 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.76 Safari/537.36',  
                    'Referer' : hosturl+"/app/member/FT_header.php?uid="+uid+"&showtype=&langx=zh-cn&mtype=4"}
        OnlineUrl = hosturl+"/app/member/mem_online.php?uid="+uid
        print  OnlineUrl
        request  = urllib2.Request(OnlineUrl, '', headers)
        #response = urllib2.urlopen(hosturl+"/app/member/mem_online.php?uid="+uid,timeout=10)  
        response = urllib2.urlopen(request,timeout=10)  
        online_text= response.read()  
        response.close()
        print OnlineUrl
        print online_text
        if online_text:
            print "Account_LoginOut"
            GetUid=1
    except Exception as e :
        print e
        GetUid=1
        print "Account_Login_Error"
    ###################################
    if GetUid: 
        #hosturl="http://112.78.26.130/"
#         postData = {'langx' : langx,  
#             'username' : username,  
#             'passwd' : passwd
#             }
#         postData = urllib.urlencode(postData)
#         hosturl  = hosturl+'/app/member/new_login.php'
#         print hosturl
#         req = urllib2.Request(hosturl, postData, headers)  
#         html = urllib2.urlopen(req).read()
#         print html
        datec = logindate(2,langx,username,passwd,hosturl)   
        print datec
        htmlc =datec['html'].split('|')
        uid = htmlc[3]
        HgUpDate(uid,datec['username'],datec['passwd'])#更新数据库UID
    ################################
    return uid


