ps -ef | grep -w 'balance6.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/balance6.py
