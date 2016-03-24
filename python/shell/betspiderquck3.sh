ps -ef | grep -w 'cspidercodequck3.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/cspidercodequck3.py
