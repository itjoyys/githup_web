ps -ef | grep -w 'spiderGuncode.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/spiderGuncode.py
