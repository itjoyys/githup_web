ps -ef | grep -w 'balance13.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/balance13.py
