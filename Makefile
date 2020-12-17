install:
	composer install
	
lint:
	composer run-script phpcs -- --standard=PSR12 src bin tests
	
test:
	composer run-script test
	
test-coverage:
	composer run-script test -- --coverage-clover build/logs/clover.xml
