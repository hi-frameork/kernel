#!/bin/env sh

# 容器名称
CONTAINER=$(basename `pwd`)-$(echo `pwd` | md5 | cut -c1-10)
# 单元测试运行镜像
IMAGE=anoxia/php-swoole:7.4-alpine3.12
WORKDIR=/var/www
# 目录挂载
MOUNT="-v `pwd`:${WORKDIR}"
# 服务端口
PORT=9527

echo '> 服务正在启动:' $(date '+%Y-%m-%d %H:%M:%S')

case $1 in
  tests)
    COMMAND="docker run --name ${CONTAINER} ${MOUNT} ${IMAGE} php ${WORKDIR}/vendor/bin/phpunit --config=${WORKDIR}/phpunit.xml"
  ;;
  cs)
    COMMAND="docker run --name ${CONTAINER} ${MOUNT} ${IMAGE} php ${WORKDIR}/vendor/bin/php-cs-fixer fix --config=${WORKDIR}/.php-cs-fixer.dist.php"
  ;;
esac

# echo ${COMMAND}
docker stop ${CONTAINER} >> /dev/null
docker rm ${CONTAINER} >> /dev/null
eval $COMMAND
echo ''

