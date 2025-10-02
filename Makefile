# Makefile for php-doofinder testing with Docker
# Usage: make <target> (e.g., make install, make test)

# Variables
PHP_VERSION ?= 8.3
IMAGE_BASE ?= php-doofinder
COMPOSER_IMAGE ?= composer:latest

# Install Composer dependencies (dev mode)
install:
	docker run --rm -v $(PWD):/app -w /app $(COMPOSER_IMAGE) install --no-scripts --dev

# Build custom Docker image (if using Dockerfile)
# Tags images as: php-doofinder-<version>
build:
	docker build --build-arg PHP_VERSION=$(PHP_VERSION) -t $(IMAGE_BASE)-$(PHP_VERSION) .

# Run tests (builds image with dependencies)
test: build
	docker run --rm $(IMAGE_BASE)-$(PHP_VERSION)

# Run tests via Composer script
test-composer:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_BASE)-$(PHP_VERSION) composer tests

# Clean vendor/ (for branch switches)
clean:
	rm -rf vendor/

# Full cycle: clean, install, test
ci: clean install test

# Test all supported PHP versions (7.4 and 8.3)
test-all:
	@for version in 7.4 8.3; do \
		echo "Testing PHP $$version..."; \
		make test PHP_VERSION=$$version; \
	done

# Specific convenience targets to run tests for 7.4 and 8.3
# They build and run a local image named `php-doofinder-<version>`
test-7.4:
	@$(MAKE) test PHP_VERSION=7.4

test-8.3:
	@$(MAKE) test PHP_VERSION=8.3

# Lint/check (if you add later; placeholder)
lint:
	@echo "No linter defined; add php-cs-fixer or similar if needed"

# Generate documentation
docs:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_BASE)-$(PHP_VERSION) php phpDocumentor.phar -d src/ -t doc/

.PHONY: install build test test-composer clean ci lint docs test-7.4 test-8.3 test-all
