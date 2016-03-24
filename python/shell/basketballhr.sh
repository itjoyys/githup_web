ps -ef | grep -w 'basketballhr.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/basketballhr.py
