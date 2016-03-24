ps -ef | grep -w 'cspidercodequck1.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/cspidercodequck1.py
