/**
 * AJAX 方法
 * @param $url=>提交地址、$data数据、$get提交方式
 * @return submitSuccess();submitLoser();
 * @author VIAL
 */
function butSubmit($url,$data,$get) {
    //创建XMLHttpRequest对象
    var xmlHttp = new XMLHttpRequest();

    //配置XMLHttpRequest对象
    xmlHttp.open($get, $url, true);
    if ($get == 'POST') {
        xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
    }
    //发送请求
    xmlHttp.send($data);

    //设置回调函数
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (xmlHttp.responseText == '200') {
                submitSuccess(xmlHttp.responseText);
            } else {
                submitLoser(xmlHttp.responseText);
            }
        }
    }

}

