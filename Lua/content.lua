--here is lua script
ngx.header.content_type = "text/html";
ngx.header.charset = "utf-8";
--ngx.say('sss2233hello lua! by michael');
-- url 参数获取
local args = ngx.req.get_uri_args();  
 
local json  = require 'cjson'
local db = require 'dbutil'
local config = require 'config'


--local sql = "SELECT * FROM `sch_info` where school_id <= '100003'";

--local sql = "SELECT * FROM `sch_info` where school_id <= '100003'";
--local  db  = ''

--[[if args.db~=nil and args.db~='' then
	 config.mysql_read.database= args.db;
end
--]]
--ngx.say(config.mysql_read.database);
--ngx.exit(500);

local  sql  = '';
if (args.sql ~='' and  args.sql ~= nil) then
	 sql  = args.sql;
else
	--ngx.log(ngx.ERR,'sql sqlstate is emtpy')
	db.echo_json('error','sql sqlstate is emtpy');
	return ngx.exit(500);
end



--ngx.print(#arr);

local  index  = {};

function  index.run(sql)


	local ret,result, sqlstate = db.query(sql);
	if ret==false or ret==nil then
	    db.echo_json('error',result,{})
	    return ngx.exit(500);
	else
		db.echo_json("success",'',result);
		return ngx.exit(500);
	end
	
end


index.run(sql);



--local co = coroutine.create(function (sql)
--	       require "dbutil"
 --          local res = dbutil.query(sql);
  --         dbutil.echo_json(res);
   --      end)
--coroutine.resume(co, sql)
--run(sql);
--ngx.thread.spawn(run,sql);


--ngx.print(json.encode(res));

 