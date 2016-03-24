ps -ef | grep -w 'haltfootball.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/haltfootball.py
