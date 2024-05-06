.PHONY: help
## help: Print help
help:
	@sed -n 's/^##//p' ${MAKEFILE_LIST} | column -t -s ':' |  sed 's/^/ /'

## shell: Attach current container
.PHONY: shell
shell: info
	@sh tests/make.sh shell

## stop: Stop current container
.PHONY: stop
stop: info
	@sh tests/make.sh stop

## tests: Run tests
.PHONY: tests
tests: info
	@sh tests/make.sh tests

## check: Run code check
.PHONY: check
check: info
	@echo '# Code check'
	@sh tests/make.sh check

## update: Run composer update
.PHONY: update
update: info
	@echo '# update vender'
	@sh tests/make.sh update

## install: Run composer install
.PHONY: install
install: info
	@echo '# install vender'
	@sh tests/make.sh install

## cs: Code formatting
.PHONY: cs
cs: info
	@echo '> Code style format'
	@sh tests/make.sh cs

info:
	@echo "> Environment info"
	@echo 'basedir:' $(shell pwd)
	@echo 'os:     ' $(shell uname | awk '{print tolower($$0)}')
	@echo 'arch:   ' $(shell uname -m)
	@echo ""
