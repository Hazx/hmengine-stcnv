#!/bin/bash

stcnv_img=hmengine-stcnv
stcnv_ver=2.0

## 清理工作目录
if [ -e build_${stcnv_img} ];then
    rm -fr build_${stcnv_img}
fi
if [ -e output/${stcnv_img}-${stcnv_ver}.tar ];then
    rm -fr output/${stcnv_img}-${stcnv_ver}.tar
fi

mkdir -p build_${stcnv_img}
mkdir -p output

## 构建
cp -R build build_${stcnv_img}/
cat <<EOF > build_${stcnv_img}/build/Dockerfile
FROM ubuntu:20.04
LABEL maintainer="hazx632823367@gmail.com"
LABEL Version="${stcnv_ver}"
COPY * /root/stcnv-build/
COPY nginx-conf /root/stcnv-build/nginx-conf
COPY api /root/stcnv-build/api
COPY custom-dict /root/stcnv-build/custom-dict
RUN bash /root/stcnv-build/IDR-build-sh
CMD /web_server/webserver.sh
EOF

## 编译与导出
docker build -t ${stcnv_img}:${stcnv_ver} build_${stcnv_img}/build/
docker save -o output/${stcnv_img}-${stcnv_ver}.tar ${stcnv_img}:${stcnv_ver}

## 清理垃圾
docker rmi ${stcnv_img}:${stcnv_ver}
rm -fr build_${stcnv_img}

echo "Docker build finished."
echo "Image name: ${stcnv_img}:${stcnv_ver}"
echo "Image Path: output/${stcnv_img}-${stcnv_ver}.tar"