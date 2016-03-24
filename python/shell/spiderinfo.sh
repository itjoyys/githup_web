ps -ef | grep -w 'spiderinfo.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/spiderinfo.py
