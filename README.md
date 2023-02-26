# HMengine-stcnv

HMengine-stcnv 是一个集成了简繁体中文转换模块及 API 接口的 Docker 镜像，支持简繁中文互转，支持港台异体字转换，支持大陆及台湾地区用语转换，支持自定义转换字典。如果你有需要可以拿去参考使用。

对应镜像及版本：`hazx/hmengine-stcnv:2.2`

## 转换效果

`你記憶體容量也太小了吧` => `你内存容量也太小了吧`

`这点小事自己动手啊` => `這點小事自己動手啊`

## 镜像所包含模块及项目

- [Nginx](http://nginx.org/): 1.22.1
- [PHP](https://www.php.net/): 8.0.26
- [OpenCC](https://github.com/BYVoid/OpenCC): 1.1.6
- [opencc4php](https://github.com/nauxliu/opencc4php): 56973eb

# 使用镜像

你可以直接下载使用我编译好的镜像 `docker pull hazx/hmengine-stcnv:2.2`，你也可以参自行编译和打包镜像。

## 映射端口

- 5000: http协议

## 映射目录（可选）

- 日志目录：`/web_server/web_log`

## 创建容器

```shell
docker run -d --name stcnv -p 5000:5000 hazx/hmengine-stcnv:2.2
```

## 调用简繁转换接口

### 请求

- 请求方式: `POST`
- 请求路径: `/convert` 或 `/?convert`
- 请求参数: 
  - text: 要转换的文本
  - mode: 转换模式，参数如下
    - s2t: 简体中文 转 繁体中文
    - s2thk: 简体中文 转 繁体中文（香港地区异体字）
    - s2ttw: 简体中文 转 繁体中文（台湾地区异体字）
    - t2s: 繁体中文 转 简体中文
    - thk2s: 繁体中文（香港地区异体字） 转 简体中文
    - ttw2s: 繁体中文（台湾地区异体字） 转 简体中文

### 返回样式

以 JSON 形式返回数据及信息：
```text
{
    "code": 执行结果码,
    "data": {
        "mode": 当前转换模式,
        "text": 转换后的文本
    },
    "msg": 错误提示,
    "ver": 程序版本号,
    "execTime": 程序执行时间
}
```

### 执行结果码
- 0: 正常结束
- 1: 缺少必要参数
- 2: mode 参数错误

# 编译和打包

*需要注意，编译和打包阶段需要 Docker 环境，且依赖互联网来安装编译和运行环境。*

执行编译和打包：

```shell
bash build.sh
```

镜像构建完成后，将保存至 `output` 目录。