ps -ef | grep -w 'balance14.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/balance14.py
