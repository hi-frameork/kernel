#!/bin/env sh

# 服务端口
Port=9520
# 容器名称
Name=$(basename $(pwd))-$(echo $(pwd) | md5 | cut -c1-10)
# 单元测试运行镜像
Image=php:8.1-alpine
# 工作目录
WorkDir=/var/www
# 目录挂载
Mount="-v $(pwd)/tests/php.ini:/usr/local/etc/php/conf.d/php.ini -v $(pwd):${WorkDir}"
# 日志路径
LogFile=
# 通过是否安装 docker 判断是否在 docker 容器内(没有 docker 命令视为在容器内)
InDocker=$(which docker | wc -l)
# 服务器启动最大等待次数
HealthCheckNum=20

# 启动服务命令
CmdRunStart="php -S 0.0.0.0:80"
# 单元测试命令
CmdRunTest="${WorkDir}/vendor/bin/phpunit --config=${WorkDir}/phpunit.xml"
# 代码检查命令
CmdRunCodeCheck="php vendor/bin/psalm"
# 代码格式化命令
CmdRunCodeSniffer="php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php"
# composer 安装
CmdRunComposerInstall="composer install"
# composer 更新
CmdRunComposerUpdate="composer update"

# 启动服务
RunServer() {
  echo "> docker run -d --name ${Name} -p ${Port}:80 ${CmdRunStart}"
  if [ $(docker ps -a | grep ${Name} | wc -l) -eq 1 ]; then
    docker stop ${Name} >> /dev/null
    docker rm ${Name} >> /dev/null
  fi

  docker run --name ${Name} ${Mount} -d -p ${Port}:80 ${Image} ${CmdRunStart} >> /dev/null
  docker logs -f ${Name} &

  # 通过服务启动端口检查服务是否启动成功
  # 最大等待 10 秒
  for i in $(seq 1 $HealthCheckNum); do
    if [ $(netstat -an | grep ${Port} | wc -l) -eq 1 ]; then
      break
    fi
    sleep 0.2
  done

  if [ $(docker ps | grep ${Name} | wc -l) -eq 0 ]; then
    echo '# 服务启动失败'
    echo ''
    exit 0
  fi
}

# 在容器中运行参入的命令
RunInContainer() {
  echo '> '$1
  if [[ ${InDocker} -eq "0" ]]; then
    ($1)
  else
    if [ $(docker ps | grep ${Name} | wc -l) -eq 0 ]; then
      RunServer
    fi
    docker exec -it ${Name} sh -c "cd ${WorkDir}; $1"
  fi
}

# watch 函数，启动服务
StartServer() {
  RunServer
  echo '# 服务地址: http://0.0.0.0:'${Port}
  echo '# 变更时间:' $(date '+%Y-%m-%d %H:%M:%S')
  echo '# 正在监听文件变化...'
}

# shell 函数，进入容器
AttachContainer() {
  if [[ ${InDocker} -eq "0" ]]; then
    echo '# 已经在容器中了'
    echo ''
    exit 0
  fi

  if [ $(docker ps | grep ${Name} | wc -l) -eq 0 ]; then
    RunServer
  fi

  # 安装依赖并进入容器
  docker exec -it ${Name} sh -c "cd ${WorkDir}; sh"
}

# 停止服务
StopServer() {
  echo "> docker stop ${Name}"
  if [ $(docker ps | grep ${Name} | wc -l) -eq 0 ]; then
    echo '# 服务未启动'
    echo ''
    exit 0
  fi

  echo '# 停止服务'
  docker stop ${Name} >> /dev/null
  docker rm ${Name} >> /dev/null
}

# 根据参数执行对应的函数
case $1 in
shell)
  AttachContainer
  ;;
tests)
  RunInContainer "${CmdRunTest}"
  ;;
check)
  RunInContainer "${CmdRunCodeCheck}"
  ;;
cs)
  RunInContainer "${CmdRunCodeSniffer}"
  ;;
install)
  RunInContainer "${CmdRunComposerInstall}"
  ;;
update)
  RunInContainer "${CmdRunComposerUpdate}"
  ;;
stop)
  StopServer
  ;;
esac
