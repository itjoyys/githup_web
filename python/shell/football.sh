ps -ef | grep -w 'football.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/football.py
