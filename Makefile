.PHONY: help install deps-stable deps-low tests coverage cs cs-fix phpstan phpstan-baseline

# Executables
COMPOSER = composer
PHPUNIT = vendor/bin/phpunit

# Default target
.DEFAULT_GOAL = help

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

install: ## Install dependencies
	$(COMPOSER) install

deps-stable: ## Update dependencies to stable
	$(COMPOSER) update --prefer-stable

deps-low: ## Update dependencies to lowest
	$(COMPOSER) update --prefer-lowest

deps-bump:
	$(COMPOSER) bump --dev-only

tests: ## Run tests
	$(PHPUNIT)

coverage: ## Run tests with coverage
	$(PHPUNIT) --coverage-html=coverage

cs: ## Check code style
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix: ## Fix code style
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix

phpstan: ## Run static analysis
	vendor/bin/phpstan analyse

phpstan-baseline: ## php static code analyse
	vendor/bin/phpstan --memory-limit=-1 --generate-baseline

rector: # Run rector
	vendor/bin/rector

ci: rector cs phpstan tests ## Run all ci tools

ci-stable: deps-stable rector cs phpstan tests ## Install lowest dependencies and run all ci tools

ci-lowest: deps-low rector cs phpstan tests ## Install highest dependencies and run all ci tools

ci-github: ## Run github pipeline to validate workflow (prevent push-and-pray)
	act push --platform ubuntu-latest=catthehacker/ubuntu:act-latest