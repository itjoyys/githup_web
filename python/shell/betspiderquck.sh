ps -ef | grep -w 'cspidercodequck.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/cspidercodequck.py
