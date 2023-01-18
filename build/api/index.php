<?php

// 请求方式: POST
// 请求路径: /convert 或 /?convert
// 请求参数: text: 要转换的文本
// 　　　　  mode: 转换模式
// 　　　　       s2t: 简体中文 转 繁体中文
// 　　　　       s2thk: 简体中文 转 繁体中文（香港地区异体字）
// 　　　　       s2ttw: 简体中文 转 繁体中文（台湾地区异体字）
// 　　　　       t2s: 繁体中文 转 简体中文
// 　　　　       thk2s: 繁体中文（香港地区异体字） 转 简体中文
// 　　　　       ttw2s: 繁体中文（台湾地区异体字） 转 简体中文

// 返回结果: JSON
// 　　　　  code: 执行结果码
// 　　　　  data: 主体数据（包含mode、text）
// 　　　　        mode: 当前执行的转换模式
// 　　　　        text: 转换后的文本
// 　　　　  msg: 错误提示信息
// 　　　　  ver: 当前程序版本
// 　　　　  execTime: 程序执行时长

// 结果码: 0 正常结束
// 　　　  1 缺少必要参数
// 　　　  2 mode 参数错误

// 配置
$stcnv_ver = "1.1";

// 记录程序开始时间
$execTimeStart = microtime(true);

// 判断传参
if(!isset($_GET['convert'])){
    echo "ok.";
    die;
}
if(!isset($_POST['text'])){
    returnErrorJson("缺少参数 text", 1);
    die;
}else if(!isset($_POST['mode'])){
    returnErrorJson("缺少参数 mode", 1);
    die;
}

// 执行转换
$text_orig = $_POST['text'];
$cnv_mode = addslashes($_POST['mode']);
$text_cnv = stcnv($text_orig, $cnv_mode);

// 返回结果
returnCnvJson($text_cnv, $cnv_mode);
exit;



// 返回执行结果  传参: 错误提示 执行结果码
function returnCnvJson($text, $mode){
    global $execTimeStart;
    global $stcnv_ver;
    $execTimeEnd = microtime(true);
    $execTime = number_format($execTimeEnd - $execTimeStart, 2);
    $json_array = array(
        "code" => 0,
        "data" => array(
            "mode" => $mode,
            "text" => $text
        ),
        "msg" => "",
        "ver" => $stcnv_ver,
        "execTime" => $execTime
    );
    $json_data = json_encode($json_array, JSON_UNESCAPED_UNICODE);
    header('Content-Type: application/json');
    echo $json_data;

    return;
}

// 返回错误信息  传参: 错误提示 执行结果码
function returnErrorJson($msg, $code){
    global $execTimeStart;
    global $stcnv_ver;
    $execTimeEnd = microtime(true);
    $execTime = number_format($execTimeEnd - $execTimeStart, 2);
    $json_array = array(
        "code" => $code,
        "data" => array(),
        "msg" => $msg,
        "ver" => $stcnv_ver,
        "execTime" => $execTime
    );
    $json_data = json_encode($json_array, JSON_UNESCAPED_UNICODE);
    header('Content-Type: application/json');
    echo $json_data;

    return;
}

// 执行简繁转换  传参: 文本、转换模式
function stcnv($text_orig, $cnv_mode){
    if($cnv_mode == "s2t"){
        $stcnv_fun = opencc_open("s2t.json");
    }else if($cnv_mode == "s2thk"){
        $stcnv_fun = opencc_open("s2hk.json");
    }else if($cnv_mode == "s2ttw"){
        $stcnv_fun = opencc_open("s2twp.json");
    }else if($cnv_mode == "t2s"){
        $stcnv_fun = opencc_open("t2s.json");
    }else if($cnv_mode == "thk2s"){
        $stcnv_fun = opencc_open("hk2s.json");
    }else if($cnv_mode == "ttw2s"){
        $stcnv_fun = opencc_open("tw2sp.json");
    }else{
        returnErrorJson("mode 参数错误", 2);
        die;
    }
    $text_cnv = opencc_convert($text_orig, $stcnv_fun);
    opencc_close($stcnv_fun);

    return $text_cnv;
}





