ps -ef | grep -w 'spiderreResultcode.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/spiderreResultcode.py
