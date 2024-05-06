.PHONY: help
## help: Print help
help:
	@sed -n 's/^##//p' ${MAKEFILE_LIST} | column -t -s ':' |  sed 's/^/ /'

## tests: Run tests
.PHONY: tests
tests: info
	@watchexec -w src -w tests sh tests/make.sh tests

## cs: Code formatting
.PHONY: cs
cs: info
	@echo '> Code style format'
	@sh tests/make.sh cs

info:
	@echo "> 环境信息"
	@echo 'basedir:' $(shell pwd)
	@echo 'os:     ' $(shell uname | awk '{print tolower($$0)}')
	@echo 'arch:   ' $(shell uname -m)
	@echo ""
