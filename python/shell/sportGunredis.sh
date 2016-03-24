ps -ef | grep -w 'spiderGun.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/spiderGun.py 
