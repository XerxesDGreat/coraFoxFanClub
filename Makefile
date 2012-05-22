####################################################
# definitions
####################################################
PHP_FILES := $(shell find . -name '*.php' | grep -v "\/build\/")
BUILD_NO := $(shell ./scripts/getBuildNo.sh)
SSH_PATH := "cora4793@corafoxfanclub.com"
PROD_DB := "corajoomla15.db.8047279.hostedresource.com"
DB_NAME := "corajoomla15"

####################################################
# generic sources
####################################################
.PHONY: all clean
all: php-lint

deploy: php-lint compress push clean-deploy

buildNo: echo $(BUILD_NO)

####################################################
# php lint
####################################################
.PHONY: php-lint $(PHP_FILES:%=php-lint-%)

php-lint: $(PHP_FILES:%=php-lint-%)

$(PHP_FILES:%=php-lint-%): php-lint-%: build/%.php-lint-ok

$(PHP_FILES:%=build/%.php-lint-ok): build/%.php-lint-ok: %
	@mkdir -p $(dir $@)
	@php -l $^
	@touch $@

####################################################
# compress
####################################################
.PHONY: compress

compress: php-lint clean-compress
	tar -czf build/$(BUILD_NO).tar --exclude=".git" --directory build/ $(BUILD_NO)

####################################################
# pull db
####################################################
.PHONY: pull-db

pull-db:
	ssh $(SSH_PATH) 'mysqldump -h $(PROD_DB) -u $(DB_NAME) -pIrtpws2b $(DB_NAME) > ~/tmp/dbdump'
	scp $(SSH_PATH):~/tmp/dbdump tmp/dbdump

####################################################
# copy-db-from-prod
####################################################
.PHONY: copy-db-from-prod

copy-db-from-prod: pull-db update-local-db

####################################################
# update local db
####################################################
.PHONY: update-local-db

update-local-db:
	mysql -u root -peef5reid $(DB_NAME) < tmp/dbdump

####################################################
# push
####################################################
.PHONY: push

push: compress
	scp build/$(BUILD_NO).tar $(SSH_PATH):~/build/$(BUILD_NO).tar
	ssh $(SSH_PATH) 'tar -xzf build/$(BUILD_NO).tar -C build/; \
		chmod +x ./build/$(BUILD_NO)/scripts/setup.sh; \
		./build/$(BUILD_NO)/scripts/setup.sh $(BUILD_NO); \
		ln -nfs ~/build/$(BUILD_NO) ~/html'

####################################################
# cleaning
####################################################
.PHONY: clean cleanup clean-php-lint clean-compress clean-deploy

clean-all: clean-php-lint clean-compress

clean-deploy: clean-compress

clean-php-lint:
	@rm -f $(PHP_FILES:%=build/%.php-lint-ok)

clean-compress:
	@rm -fr build/$(BUILD_NO) build/build.tar
