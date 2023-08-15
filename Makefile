.PHONY: help
## help: 打印帮助信息
help:
	@echo "使用说明:"
	@sed -n 's/^##//p' ${MAKEFILE_LIST} | column -t -s ':' |  sed 's/^/ /'

## tests: 单元测试
.PHONY: tests
tests: info
	@watchexec -w src -w tests sh tests/make.sh tests

## cs: 代码优化
.PHONY: cs
cs: info
	@echo '> Code style format'
	@sh tests/make.sh cs

# 打印环境信息
info:
	@echo "> 环境信息"
	@echo 'basedir:' $(shell pwd)
	@echo 'os:     ' $(shell uname | awk '{print tolower($$0)}')
	@echo 'arch:   ' $(shell uname -m)
	@echo ""
