ps -ef | grep -w 'spidercodeother.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/spidercodeother.py
