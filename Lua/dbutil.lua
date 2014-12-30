module("dbutil", package.seeall)
local mysql_pool = require("mysql_pool")
local json  = require('cjson')

function query(sql)

    local ret,result, sqlstate = mysql_pool:query(sql)
    if not ret then
        --ngx.log(ngx.ERR, "query db error. res: " .. (res or "nil"))
        return ret,result, sqlstate
    end

    return ret,result, sqlstate
end


--返回输出结果
function echo_json(type,msg,data)
    local result = {['type']=type,['msg']=msg,['data']=data}
    local data = json.encode(result);
    ngx.print(data);
end