# Contributing to RelayWarden PHP SDK

Thank you for your interest in contributing to the RelayWarden PHP SDK! This document provides guidelines and instructions for contributing.

## Code of Conduct

This project adheres to a Code of Conduct. By participating, you are expected to uphold this code.

## Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer 2.0 or higher
- Git

### Setting Up the Development Environment

1. Fork the repository
2. Clone your fork:
   ```bash
   git clone https://github.com/your-username/relaywarden-php-sdk.git
   cd relaywarden-php-sdk
   ```
3. Install dependencies:
   ```bash
   composer install
   ```

## Development Workflow

### Making Changes

1. Create a new branch from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```
2. Make your changes
3. Ensure code follows PSR-12 coding standards
4. Write or update tests
5. Run tests:
   ```bash
   composer test
   ```
6. Run static analysis:
   ```bash
   composer analyse
   ```
7. Commit your changes with clear, descriptive messages

### Coding Standards

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Use type hints for all parameters and return types
- Add PHPDoc comments for public methods
- Keep functions focused and single-purpose

### Testing

- Write tests for all new functionality
- Ensure all tests pass before submitting
- Aim for high test coverage
- Use PHPUnit for testing

### Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/) format:

```
type(scope): subject

body (optional)

footer (optional)
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

Example:
```
feat(messages): add support for message cancellation

Add the ability to cancel pending messages via the API.
This includes retry logic and proper error handling.
```

## Submitting Changes

1. Push your branch to your fork
2. Create a Pull Request targeting the `main` branch
3. Fill out the PR template completely
4. Ensure all CI checks pass
5. Address any review feedback

### Pull Request Checklist

- [ ] Code follows PSR-12 standards
- [ ] Tests added/updated and passing
- [ ] Documentation updated
- [ ] No new PHPStan errors
- [ ] Commit messages follow Conventional Commits
- [ ] PR description is clear and complete

## Reporting Issues

When reporting bugs or requesting features:

1. Check existing issues to avoid duplicates
2. Use the appropriate issue template
3. Provide clear steps to reproduce (for bugs)
4. Include PHP version, SDK version, and environment details
5. Add code examples when relevant

## Documentation

- Update README.md for user-facing changes
- Add PHPDoc comments for new public APIs
- Keep code examples up to date

## Questions?

- Open a discussion for questions
- Check existing issues and discussions
- Review the README for common usage patterns

Thank you for contributing! 🎉
