init:
	composer install && \
	sh -l ./.script/install-wp-tests.sh && \
	NVM_DIR="$${HOME}/.nvm" && . "$${NVM_DIR}/nvm.sh" && nvm use && \
	npm install && \
	cd test/plugin && \
	composer install

composer-update:
	composer update && \
	cd test/plugin && \
	composer update

tests:
	composer run tests

fix:
	composer run fix

lint:
	composer run lint

create-doc:
	rm -rf doc && composer run create-doc

deploy-doc:
	make doc && npm run deploy-doc
