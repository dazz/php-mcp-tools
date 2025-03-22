.PHONY: help install tests coverage cs csfix stan analyze clean

# Executables
COMPOSER = composer
PHPUNIT = vendor/bin/phpunit

# Default target
.DEFAULT_GOAL = help

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

install: ## Install dependencies
	$(COMPOSER) install

deps-stable: ## Update dependencies
	$(COMPOSER) update --prefer-stable

deps-low: ## Update dependencies
	$(COMPOSER) update --prefer-lowest

tests: ## Run tests
	$(PHPUNIT)

coverage: ## Run tests with coverage
	$(PHPUNIT) --coverage-html=coverage

cs: ## Check code style
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix: ## Fix code style
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix

phpstan: ## Run static analysis
	vendor/bin/phpstan analyse src tests --level=max

rector: # Run rector
	vendor/bin/rector

ci: ci-stable ## Run all ci tools

ci-stable: deps-stable rector cs phpstan tests

ci-lowest: deps-low rector cs phpstan tests