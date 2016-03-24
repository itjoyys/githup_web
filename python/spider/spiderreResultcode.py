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
DB_USER_P = globalConfig.DB_USER_P
DB_PASSWD_P = globalConfig.DB_PASSWD_P
DB_NAME = globalConfig.DB_NAME
DB_NAME_P = globalConfig.DB_NAME_P  
#
#


THREAD_LIMIT = 13
jobs = Queue.Queue(5)
singlelock = threading.Lock()
info = Queue.Queue()
  
headers = {"User-Agent": "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1) Gecko/20090624 Firefox/3.5",
             "Referer": globalConfig.dominAll} 


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

     


def getResult(Uid,url,uid,time=10):
    req = urllib2.Request(domain+"/app/member/mem_online.php?uid="+uid,None,i_headers)
    urllib2.urlopen(req,None,timeout=time)
    print url+uid
    req = urllib2.Request(url+uid,None,headers)
    response = urllib2.urlopen(req,None,timeout=time) 
    html = response.read()
#     cc=open('19.html')
#     html = cc.read()
    response.close()
    tables = {19:'bet_match',  20:'lq_match',  21:'tennis_match', 22:'volleyball_match', 25:'baseball_match'}
    operator.get(Uid)(html,tables[Uid]) 
    return html            
   

def FBBRO19(html,table):
    html = ''.join(html.split())
    re_cdata=re.compile('<tr>(.*?)<\/tr>',re.I) 
    teamgroups  = re.compile(re_cdata).findall(html)
    temp = []
    for key,value in enumerate(teamgroups):
        re_cdata=re.compile('showLEG\(\'(.*?)\'\)(.*?)leg_bar\">(.*?)<\/span>',re.I) 
        temp  += re.compile(re_cdata).findall(value) 
    gourps = []
    if(temp):
        for key,value in enumerate(temp):   
            data = {value[0]:value[2]}
            gourps = dict( gourps,  **data)
        re_cdata=re.compile('<tr>(.*?)<\/tr>',re.I) 
        html  = re_cdata.sub('',html)
        re_time=re.compile('<trclass="time">(.*?)<\/tr>',re.I) 
        html  = re_time.sub('',html)
        re_zhushi=re.compile('<!--(.*?)-->',re.I) 
        html  = re_zhushi.sub('',html)    
        reg = r'<tr(.*?)<\/tr>'
        title = re.compile(reg).findall(html) 
        print title
        i=0
        j=len(title)//3
        print j
        bet = [[0 for col in range(7)] for row in range(j)]
        for key,value in enumerate(title):
            if((key%3==0) and (not key==0)): 
                i+=1 
            if(key%3==0):
                #ID正则
                reg = r'id=\"(.*?)\"'
                ID = re.compile(reg).findall(value)
                reg = r'time\">(.*?)<br>'
                matchdate = re.compile(reg).findall(value) 
                if (table =='bet_match'):  
                    reg = r'\'zh-cn\'\);\">(.*?)&nbsp;'
                    mb = re.compile(reg).findall(value) 
                    reg = r'team_h_ft">(.*?)&nbsp;'
                    tg = re.compile(reg).findall(value)
                elif (table =='baseball_match'):
                    reg = r'team_c\">(.*?)&nbsp;'
                    mb = re.compile(reg).findall(value) 
                    reg = r'team_h\">(.*?)&nbsp;'
                    tg = re.compile(reg).findall(value)                  
                ID = ID[0].split('_')
                team = gourps[ID[1]]
                ID = ID[2]
                bet[i][0]=ID
                bet[i][3]=matchdate[0]
                bet[i][4]=mb[0]
                bet[i][5]=tg[0]
                bet[i][6]=team
            if(key%3==1):
                #取半场比分
                reg = r'hidden;">(.*?)</span>'
                fscore = re.compile(reg).findall(value)
                if not fscore[0].isdigit():
                    fscore=['%d' %-1,'%d' %-1];
                bet[i][1]=fscore                       
            if(key%3==2):
                #取主场比分
                reg = r'hidden;">(.*?)</span>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1];      
                bet[i][2]=mscore 
        for key,ti in enumerate(bet):
            try:
                con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
                cur = con.cursor()
                print ti
                cur.execute("select Match_ID from `"+table+"` where Match_name='"+str(ti[6])+"' and Match_Master='"+str(ti[4])+"' and Match_Guest='"+str(ti[5])+"' and Match_Date='"+str(ti[3])+"'  ")
                rows = cur.fetchall()
                ids = ''
                for row in rows:
                    ids+=str(row[0])+','
                ids =ids[0:-1]
                if not ids =='':
                    sql = "Update `"+table+"` set MB_Inball_HR='"+ti[1][0]+"',TG_Inball_HR='"+ti[1][1]+"',MB_Inball='"+ti[2][0]+"',TG_Inball='"+ti[2][1]+"' where Match_ID in ("+ids+")" 
                    cur.execute(sql)
                    con.commit()
                    cur.close()     
                    con.close()
                    
                    conc = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_P,charset='utf8')
                    curc = conc.cursor()
                    print ti[1]
                    print ti[2]
                    print 'ccsss'
                    sql = "Update `k_bet` set MB_Inball='"+ti[1][0]+"',TG_Inball='"+ti[1][1]+"' where match_id  in ("+ids+") and point_column in ('match_bho','match_bao','match_bdpl','match_bxpl','match_bmdy','match_bgdy','match_bhdy') and lose_ok=1 and status=0  "                                                                  
                    print sql
                    curc.execute(sql)
                    conc.commit()
                    sql = "Update `k_bet` set MB_Inball='"+ti[2][0]+"',TG_Inball='"+ti[2][1]+"' where match_id in ("+ids+") and point_column in ('match_ho','match_ao','match_dxdpl','match_dxxpl','match_bd10','match_bd20','match_bd21','match_bd30','match_bd31','match_bd32','match_bd40','match_bd41','match_bd42','match_bd43','match_bdg10','match_bdg20','match_bdg21','match_bdg30','match_bdg31','match_bdg32','match_bdg40','match_bdg41','match_bdg42','match_bdg43','match_bd00','match_bd11','match_bd22','match_bd33','match_bd44','match_bdup5','match_total01pl','match_total23pl','match_total46pl','match_total7uppl','match_bzm','match_bzg','match_bzh','match_dsdpl','match_dsspl') and lose_ok=1 and status=0  "                                                                  
                    curc.execute(sql)
                    conc.commit()
                    sql = "Update `k_bet` set MB_Inball='"+ti[1][0]+"/"+ti[2][0]+"',TG_Inball='"+ti[1][1]+"/"+ti[2][1]+"' where match_id in ("+ids+") and point_column in ('match_bqmm','match_bqmh','match_bqmg','match_bqhm','match_bqhh','match_bqhg','match_bqgm','match_bqgh','match_bqgg') and lose_ok=1 and status=0  "                                                                  
                    curc.execute(sql) 
                    conc.commit() 
                    sql = "Update `k_bet_cg` set MB_Inball='"+ti[1][0]+"',TG_Inball='"+ti[1][1]+"' where match_id in ("+ids+") and point_column in ('match_bho','match_bao','match_bdpl','match_bxpl','match_bmdy','match_bgdy','match_bhdy')  and status=0  "                                                                  
                    curc.execute(sql) 
                    sql = "Update `k_bet_cg` set MB_Inball='"+ti[2][0]+"',TG_Inball='"+ti[2][1]+"' where match_id in ("+ids+") and point_column in ('match_ho','match_ao','match_dxdpl','match_dxxpl','match_bd10','match_bd20','match_bd21','match_bd30','match_bd31','match_bd32','match_bd40','match_bd41','match_bd42','match_bd43','match_bdg10','match_bdg20','match_bdg21','match_bdg30','match_bdg31','match_bdg32','match_bdg40','match_bdg41','match_bdg42','match_bdg43','match_bd00','match_bd11','match_bd22','match_bd33','match_bd44','match_bdup5','match_total01pl','match_total23pl','match_total46pl','match_total7uppl','match_bzm','match_bzg','match_bzh','match_dsdpl','match_dsspl') and status=0 "                                                                  
                    curc.execute(sql) 
                    sql = "Update `k_bet_cg` set MB_Inball='"+ti[1][0]+"/"+ti[2][0]+"',TG_Inball='"+ti[1][1]+"/"+ti[2][1]+"' where match_id in ("+ids+") and point_column in ('match_bqmm','match_bqmh','match_bqmg','match_bqhm','match_bqhh','match_bqhg','match_bqgm','match_bqgh','match_bqgg') and status=0  "                                                                  
                    curc.execute(sql)
                    conc.commit() 
                    curc.close()     
                    conc.close()
            except MySQLdb.Error, e:
                print e
    

def FBBRO20(html,table):
    html = ''.join(html.split())
    re_cdata=re.compile('<tr>(.*?)<\/tr>',re.I) 
    teamgroups  = re.compile(re_cdata).findall(html)
    temp = []
    for key,value in enumerate(teamgroups):
        re_cdata=re.compile('showLEG\(\'(.*?)\'\)(.*?)leg_bar\">(.*?)<\/span>',re.I) 
        temp  += re.compile(re_cdata).findall(value) 
    gourps = []
    if(temp):    
        for key,value in enumerate(temp):   
            data = {value[0]:value[2]}
            gourps = dict( gourps,  **data)
        re_cdata=re.compile('<tr>(.*?)<\/tr>',re.I) 
        html  = re_cdata.sub('',html)
        re_time=re.compile('<trclass="time">(.*?)<\/tr>',re.I) 
        html  = re_time.sub('',html)
        re_zhushi=re.compile('<!--(.*?)-->',re.I) 
        html  = re_zhushi.sub('',html)    
        reg = r'<tr(.*?)<\/tr>'
        title = re.compile(reg).findall(html) 
        i=0
        j=len(title)//9
        bet = [[0 for col in range(13)] for row in range(j)]
        for key,value in enumerate(title):
            if((key%9==0) and (not key==0)): 
                i+=1 
            if(key%9==0):
                #ID正则
                reg = r'id=\"(.*?)\"'
                ID = re.compile(reg).findall(value)
                reg = r'time\">(.*?)<br>'
                matchdate = re.compile(reg).findall(value)
                reg = r'team_c\">(.*?)&nbsp;'
                mb = re.compile(reg).findall(value) 
                reg = r'team_h">(.*?)&nbsp;'
                tg = re.compile(reg).findall(value)  
                ID = ID[0].split('_')
                team = gourps[ID[1]]
                ID = ID[2]
                bet[i][0]=ID
                bet[i][9]=matchdate[0]
                bet[i][10]=mb[0]
                bet[i][11]=tg[0]
                bet[i][12]=team
            if(key%9==1):
                #第一节比分
                reg = r'<strong>(.*?)<\/strong>'
                fscore = re.compile(reg).findall(value)
                if not fscore[0].isdigit():
                    fscore=['%d' %-1,'%d' %-1]
                bet[i][1]=fscore                       
            if(key%9==2):
                #第二节比分
                reg = r'<strong>(.*?)<\/strong>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1]    
                bet[i][2]=mscore 
            if(key%9==3):
                #第三节比分
                reg = r'<strong>(.*?)<\/strong>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1]    
                bet[i][3]=mscore 
            if(key%9==4):
                #第四节比分
                reg = r'<strong>(.*?)<\/strong>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1]    
                bet[i][4]=mscore 
            if(key%9==5):
                #上半场比分
                reg = r'hr_main">(.*?)<\/td>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1]    
                bet[i][5]=mscore
            if(key%9==6):
                #下半场比分
                reg = r'hr_main">(.*?)<\/td>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1]    
                bet[i][6]=mscore  
            if(key%9==7):
                #加时
                reg = r'<strong>(.*?)<\/strong>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1]    
                bet[i][7]=mscore
            if(key%9==8):
                #全场
                reg = r'full_main">(.*?)<\/td>'
                mscore = re.compile(reg).findall(value)
                if not mscore[0].isdigit():
                    mscore=['%d' %-1,'%d' %-1]    
                bet[i][8]=mscore 
        for key,ti in enumerate(bet):
            try:
                con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
                cur = con.cursor()
                print ti
                cur.execute("select Match_ID from `"+table+"` where Match_name='"+str(ti[12])+"' and Match_Master='"+str(ti[10])+"' and Match_Guest='"+str(ti[11])+"' and Match_Date='"+str(ti[9])+"'  ")
                rows = cur.fetchall()
                ids = ''
                for row in rows:
                    ids+=str(row[0])+','
                ids =ids[0:-1]
                if not ids =='':
                    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
                    cur = con.cursor()
                    sql = "Update `"+table+"` set MB_Inball_1st='"+ti[1][0]+"',TG_Inball_1st='"+ti[1][1]+"',MB_Inball_2st='"+ti[2][0]+"',TG_Inball_2st='"+ti[2][1]+"',MB_Inball_3st='"+ti[3][0]+"',TG_Inball_3st='"+ti[3][1]+"',MB_Inball_4st='"+ti[4][0]+"',TG_Inball_4st='"+ti[4][1]+"', MB_Inball_HR='"+ti[5][0]+"',TG_Inball_HR='"+ti[5][1]+"',MB_Inball_ER='"+ti[6][0]+"',TG_Inball_ER='"+ti[6][1]+"',MB_Inball_Add  ='"+ti[7][0]+"',TG_Inball_Add='"+ti[7][1]+"',MB_Inball='"+ti[8][0]+"',TG_Inball='"+ti[8][1]+"' where Match_ID  in ("+ids+") "                                                                     
                    cur.execute(sql) 
                    con.commit()
                    cur.close()     
                    con.close()
                    
                    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_P,charset='utf8' )
                    cur = con.cursor()
                    sql = "Update `k_bet` set MB_Inball='"+ti[8][0]+"',TG_Inball='"+ti[8][1]+"' where  status=0 and  Match_ID  in ("+ids+")"                                                                     
                    cur.execute(sql) 
                    sql = "Update `k_bet_cg` set MB_Inball='"+ti[8][0]+"',TG_Inball='"+ti[8][1]+"' where status=0 and  Match_ID  in ("+ids+")"                                                                  
                    cur.execute(sql)             
                con.commit()
                cur.close()     
                con.close()
            except MySQLdb.Error, e:
                print e


def FBBRO21(html,table):
    print html
    html = ''.join(html.split())
    re_cdata=re.compile('<tr>(.*?)<\/tr>',re.I) 
    teamgroups  = re.compile(re_cdata).findall(html)
    temp = []
    for key,value in enumerate(teamgroups):
        re_cdata=re.compile('showLEG\(\'(.*?)\'\)(.*?)leg_bar\">(.*?)<\/span>',re.I) 
        temp  += re.compile(re_cdata).findall(value) 
    gourps = []
    print temp
    if(temp):
        for key,value in enumerate(temp):   
            data = {value[0]:value[2]}
            gourps = dict( gourps,  **data)
        print gourps
        re_cdata=re.compile('<tr>(.*?)<\/tr>',re.I) 
        html  = re_cdata.sub('',html)
        re_time=re.compile('<trclass="time">(.*?)<\/tr>',re.I) 
        html  = re_time.sub('',html)
        re_zhushi=re.compile('<!--(.*?)-->',re.I) 
        html  = re_zhushi.sub('',html)    
        reg = r'<tr(.*?)<\/tr>'
        title = re.compile(reg).findall(html) 
        j = len(title)
        bet = [[0 for col in range(6)] for row in range(j)]
        i=0    
        if (table =='tennis_match'):
            lennum = 8
            endstr= '_tn'
        elif (table =='volleyball_match'):
            lennum = 10
            endstr= '_tn'
        for key in range(len(title)):
            print '------------------------------'
            flag=0
            if 'b_cen' in title[key]:
                print title[key]
                reg = r'time\">(.*?)<br>'
                matchdate = re.compile(reg).findall(title[key])
                reg = r'team_c'+endstr+'\">(.*?)&nbsp;'
                print reg
                mb = re.compile(reg).findall(title[key]) 
                print mb
                reg = r'team_h'+endstr+'\">(.*?)&nbsp;'
                tg = re.compile(reg).findall(title[key])  
                reg = r'id=\"(.*?)\"'
                ID = re.compile(reg).findall(title[key])
                ID = ID[0].split('_')
                ID = ID[1]
                team = gourps[ID[0:6]]
                bet[i][2]=matchdate[0]
                bet[i][3]=mb[0]
                bet[i][4]=tg[0]
                bet[i][5]=team 
                key=key+lennum
                flag=1
            if 'full' in title[key]:
                #ID正则
                reg = r'id=\"(.*?)\"'
                ID = re.compile(reg).findall(title[key])
                ID = ID[0].split('_')
                ID = ID[2]
                bet[i][0]=ID[6:]
                #取全场比分
                reg = r'hidden;">(.*?)</span>'
                fscore = re.compile(reg).findall(title[key])
                if not fscore[0].isdigit():
                    fscore=['%d' %-1,'%d' %-1];
                bet[i][1]=fscore
                if (flag==1):
                    i+=1
        for be in bet:
            print be
        del bet[i:j]
        for key,ti in enumerate(bet):
            try:
                con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
                cur = con.cursor()
                print ti
                cur.execute("select Match_ID from `"+table+"` where Match_name='"+str(ti[5])+"' and Match_Master='"+str(ti[3])+"' and Match_Guest='"+str(ti[4])+"' and Match_Date='"+str(ti[2])+"'  ")
                rows = cur.fetchall()
                ids = ''
                for row in rows:
                    ids+=str(row[0])+','
                ids =ids[0:-1]
                if not ids =='':
                    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
                    cur = con.cursor()
                    sql = "Update `"+table+"` set MB_Inball='"+ti[1][0]+"',TG_Inball='"+ti[1][1]+"' where  Match_ID  in ("+ids+") "                                                                   
                    cur.execute(sql)
                    con.commit()
                    cur.close()     
                    con.close() 
                               
                    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_P,charset='utf8' )
                    cur = con.cursor()
                    sql = "Update `k_bet` set MB_Inball='"+ti[1][0]+"',TG_Inball='"+ti[1][1]+"' where status=0 and  Match_ID  in ("+ids+") "                                                                     
                    cur.execute(sql) 
                    
                    sql = "Update `k_bet_cg` set MB_Inball='"+ti[1][0]+"',TG_Inball='"+ti[1][1]+"' where status=0 and  Match_ID  in ("+ids+")"                                                                  
                    cur.execute(sql) 
                con.commit()
                cur.close()     
                con.close()
            except MySQLdb.Error, e:
                print e

                    
operator = {19:FBBRO19,20:FBBRO20,21:FBBRO21,22:FBBRO21,25:FBBRO19}

class spider(threading.Thread):
    def run(self):
        while 1:
            try:
                job = jobs.get(True,1)
                singlelock.acquire()
                title = getResult(job[0],domain+job[1],uid)
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
                [19,'/app/member/result/result.php?game_type=FT&langx=zh-cn&uid='],
                [20, '/app/member/result/result.php?game_type=BK&langx=zh-cn&uid='],
                [21, '/app/member/result/result_tn.php?game_type=TN&langx=zh-cn&uid='],
                [22, '/app/member/result/result_vb.php?game_type=VB&langx=zh-cn&uid='],
#              [23, '/app/member/result/result_bm.php?game_type=BM&langx=zh-cn&uid='],
#              [24, '/app/member/result/result_tt.php?game_type=TT&langx=zh-cn&uid='],
                [25, '/app/member/result/result.php?game_type=BS&langx=zh-cn&uid='],
#               [27,'/app/member/FT_browse/body_var.php?rtype=re&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&uid='],
#               [28,'/app/member/BK_browse/body_var.php?rtype=re_main&langx=zh-cn&mtype=4&page_no=0&league_id=&hot_game=&uid='],
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
