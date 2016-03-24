ps -ef | grep -w 'spidercodefu.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/spidercodefu.py
