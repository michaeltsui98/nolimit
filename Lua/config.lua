module('config', package.seeall)

local  config  = {
	mysql_read = {
		host = '172.16.0.3',
		port = 3306,
		database = 'dodoedu',
        user = 'cjsq',
        password = '1010',
        max_packet_size = 1024*1024
	},
	mysql_write = {
		host = '172.16.0.3',
		port = 3306,
        user = 'cjsq',
        password = '1010',
        database = 'dodoedu',
        max_packet_size = 1024*1024
	},
	redis = {},
	memcache = {}
}


return config