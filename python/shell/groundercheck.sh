ps -ef | grep -w 'groundercheck.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/groundercheck.py
