ps -ef | grep -w 'cspidercodequck2.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/cspidercodequck2.py
