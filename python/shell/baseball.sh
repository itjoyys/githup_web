ps -ef | grep -w 'baseball.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/baseball.py
