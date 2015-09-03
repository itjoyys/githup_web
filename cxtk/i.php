<?php
  
 $beginTime = microtime(true);
for($i = 0; $i < 100; $i ++);
echo '耗时:'.(microtime(true) - $beginTime).' s';
?>