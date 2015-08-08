<?php
set_time_limit(0);
while (true){
	sleep(30);
	echo file_get_contents('status.txt')."\n";
}
