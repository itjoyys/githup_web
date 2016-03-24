'''
Created on 2015-3-24

@author: Administrator
'''
#!/user/bin/python
#Filename:baseball.py
# -*- coding: utf-8 -*-
#encoding=utf-8

import MySQLdb
import threading
import Queue
import sportsalgorithm
import globalConfig
#
#
DB_HOST = globalConfig.DB_HOST
DB_USER = globalConfig.DB_USER
DB_PASSWD = globalConfig.DB_PASSWD
DB_USER_P = globalConfig.DB_USER_P
DB_PASSWD_P = globalConfig.DB_PASSWD_P
DB_NAME = globalConfig.DB_NAME
DB_NAME_PRI = globalConfig.DB_NAME_P  
#
#
THREAD_LIMIT = 5
jobs = Queue.Queue(5)
singlelock = threading.Lock()
info = Queue.Queue()



class spider(threading.Thread):
    def run(self):
        while 1:
            try:
                job = jobs.get(True,1)
                singlelock.acquire()
                title = setted(job)
                info.put([job,title], block=True, timeout=5)
                singlelock.release()
                jobs.task_done()
            except:
                break;
            
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
    
def setted(rs):
    
#   获取开奖号码    
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    mysql="update baseball_match set Match_JS=2 where Match_ID="+rs['Match_ID']
    cur.execute(mysql)
    con.commit()
    cur.close()     
    con.close()
#   未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER_P,DB_PASSWD_P,DB_NAME_PRI,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor ) 
    cur.execute("select * from k_bet where match_id="+rs['Match_ID']+"  and  point_column in ( 'match_ho','match_ao','match_dxdpl','match_dxxpl','match_dxdpl','match_dxxpl','match_bd10','match_bd20','match_bd21','match_bd30','match_bd31','match_bd32','match_bd40','match_bd41','match_bd42','match_bd43','match_bdg10','match_bdg20','match_bdg21','match_bdg30','match_bdg31','match_bdg32','match_bdg40','match_bdg41','match_bdg42','match_bdg43','match_bd00','match_bd11','match_bd22','match_bd33','match_bd44','match_bdup5' ) and lose_ok =1 and status=0")
    rows = cur.fetchall()
    print rows
    for row in rows:
        MB_Inball = TG_Inball = MB_Inball_HR = TG_Inball_HR = ""
        if(row['MB_Inball']==None or row['TG_Inball']==None):
            mysql = "Update `k_bet` set MB_Inball='"+str(rs['MB_Inball'])+"',TG_Inball='"+str(rs['TG_Inball'])+"' where bid = "+str(row['bid']) 
            cur.execute(mysql) 
            con.commit() 
            MB_Inball = str(rs['MB_Inball'])
            TG_Inball = str(rs['TG_Inball'])            
        else:
            MB_Inball = row['MB_Inball']
            TG_Inball = row['TG_Inball']
        match_nowscore = str(row[25])+':'+str(row[26])
        t = sportsalgorithm.make_point(row['point_column'].lower(), MB_Inball, TG_Inball, MB_Inball_HR,TG_Inball_HR, row['match_type'], row['match_showtype'], row['match_rgg'], row['match_dxgg'], match_nowscore)
        print t
        if (t["status"]==1 and not (t['mb_inball']==None or t['tg_inball']==None) ):
            #win = (t["ben_add"] + row[15]) * row[13]
            res = sportsalgorithm.win_ds(row, t['mb_inball'], t['tg_inball'])
        elif(t["status"]==2 and not (t['mb_inball']==None or t['tg_inball']==None) ):
            #win = 0
            res = sportsalgorithm.lost_ds(row, t['mb_inball'], t['tg_inball'])
        elif(t["status"]==3 and not (t['mb_inball']==None or t['tg_inball']==None) ):
            #win = row[13]
            res = sportsalgorithm.invalid_bet_ds(row, t['mb_inball'], t['tg_inball'])
        elif(t["status"]==4 and not (t['mb_inball']==None or t['tg_inball']==None) ):
            #win = (1 + row[15] / 2) * row[13]
            res = sportsalgorithm.halfwin_ds(row, t['mb_inball'], t['tg_inball'])
        elif(t["status"]==5 and not (t['mb_inball']==None or t['tg_inball']==None)  ):
            #win = row[13] / 2
            res = sportsalgorithm.halflost_ds(row, t['mb_inball'], t['tg_inball'])
        elif(t["status"]==8 and not (t['mb_inball']==None or t['tg_inball']==None) ):
            #win = row[13]
            res = sportsalgorithm.tie_bet_ds(row, t['mb_inball'], t['tg_inball']) 
    con.commit()
    cur.close()     
    con.close()
#   根据期数读取未结算的注单
    con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )
    cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )                      
    try:
        mysql="update baseball_match set Match_JS=1 where Match_ID="+rs['Match_ID']
        cur.execute(mysql)
        con.commit()
    except MySQLdb.Error, e:
        print "期数修改失败!!!"
        quit() 
        
if __name__ == '__main__':
    con = None
    urls = []
    try:
        con = MySQLdb.connect( DB_HOST,DB_USER,DB_PASSWD,DB_NAME,charset='utf8' )   
        cur = con.cursor ( cursorclass = MySQLdb . cursors . DictCursor )
        mysql="update baseball_match set Match_JS=0 where Match_JS=2"
        cur.execute(mysql) 
        con.commit() 
        cur.execute("select * from baseball_match where Match_JS =0 and ( MB_Inball >=0 or MB_Inball =-1 ) order by ID desc limit 5 ")
        rs = cur.fetchall()
        if rs:        
            workerbee(rs)
        while not info.empty():
            print 'end'
            quit()
#              print info.get()
    finally:
        if con:
            con.close()
#end of model
