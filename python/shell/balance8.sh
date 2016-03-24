ps -ef | grep -w 'balance8.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/balance8.py
