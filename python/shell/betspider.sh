ps -ef | grep -w 'cspidercode.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/cspidercode.py
