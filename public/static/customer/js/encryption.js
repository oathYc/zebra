const MXHY = {
    network: 1,
    isSend: true, // 是否可以发起请求
    switcheryMap: [],
};
try{
    (function(){

        // 获取sign
        this.getParams = function (newObj) {
            let params = {
                signKey,
            };

            if(newObj !== undefined){
                if(typeof newObj === "object"){
                    Object.assign(params, newObj);
                }
            }

            //产生一个四位的随机数
            function rand(min,max){
                return Math.floor(Math.random()*(max-min))+min
            }
            let nonce = rand(1000,9999);//随机生成4位数
            let timeStamp = Math.round((new Date().getTime())/1000).toString();//当前时间戳前十位

            params.nonce = nonce;
            params.timeStamp = timeStamp;

            let sign = MXHY.getParamsSign(MXHY.paramsToArray(params),im);
            params.sign = sign;

            return params;
        }

        //对象转换为数组
        this.paramsToArray = function (params) {

            if (typeof params == "object") {
                var arr = [];
                for (var i in params) {
                    arr.push((i + "=" + params[i]));
                }
                return arr;
            }
        }

        //生成sign参数
        this.getParamsSign = function (params, im) {

            let paramsSort = md5(params.sort().join("&")).toLowerCase();

            if (im === undefined) {
                return paramsSort;
            } else {
                return md5(paramsSort + im);
            }
        }

        //向服务器发送请求
        this.send = function (url, _method, params, callback) {
            try {

                if (!this.isSend) {
                    throw '不能重复发起请求';
                }

                this.isSend = false;

                if (params == null || params == '' || params.length <= 0) {
                    params = {};
                }


                $.ajax({
                    url: url,
                    data: params,
                    type: _method,
                    dataType: "json",
                    success: function (data) {
                        MXHY.isSend = true;

                        if (callback != undefined && typeof (callback) == "function") {
                            var content = data.data != undefined ? data.data : '';
                            callback(content,data.message, data.code);
                        }
                    },
                    statusCode: {
                        404: function () {

                            MXHY.isSend = true;

                            console.log('404');
                        }
                    },
                    error: function (msg) {

                        MXHY.isSend = true;

                        console.error(msg);
                    }
                });

            } catch (e) {
                console.log(e);
            }
        };
    }).apply(MXHY);
} catch (e) {
    console.log('对象被覆盖，请程序员仔细检查！错误信息:' + e);
}