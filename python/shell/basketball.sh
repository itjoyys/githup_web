ps -ef | grep -w 'basketball.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/basketball.py
