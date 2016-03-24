ps -ef | grep -w 'tennis.py' | grep -v grep | awk '{print $2}' | xargs kill -9
python /home/python/spider/tennis.py
