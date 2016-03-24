ps -ef | grep -w 'balance10.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/balance10.py

