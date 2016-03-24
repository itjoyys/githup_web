ps -ef | grep -w 'spidercode.py' | grep -v grep | awk '{print $2}' | xargs kill -9

