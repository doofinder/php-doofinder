# Makefile for php-doofinder testing with Docker
# Usage: make <target> (e.g., make install, make test)

# Variables
PHP_VERSION ?= 7.4
IMAGE ?= php-doofinder-tests
COMPOSER_IMAGE ?= composer:latest

# Install Composer dependencies (dev mode)
install:
	docker run --rm -v $(PWD):/app -w /app $(COMPOSER_IMAGE) install --no-scripts --dev

# Build custom Docker image (if using Dockerfile)
build:
	docker build -t $(IMAGE):$(PHP_VERSION) .

# Run tests (requires vendor/ from install)
test: install
	docker run --rm -v $(PWD):/app -w /app $(IMAGE):$(PHP_VERSION) php vendor/bin/phpunit tests/

# Run tests via Composer script
test-composer:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE):$(PHP_VERSION) composer tests

# Clean vendor/ (for branch switches)
clean:
	rm -rf vendor/

# Full cycle: clean, install, test
ci: clean install test

# Lint/check (if you add later; placeholder)
lint:
	@echo "No linter defined; add php-cs-fixer or similar if needed"

# Generate documentation
docs:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE):$(PHP_VERSION) php phpDocumentor.phar -d src/ -t doc/

.PHONY: install build test test-composer clean ci lint docs
