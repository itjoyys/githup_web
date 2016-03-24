ps -ef | grep -w 'spiderResultold.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/spiderResultold.py
