# Contributing to ZATCA Laravel

Thank you for considering contributing to the ZATCA Laravel package!

## Code of Conduct

Please be respectful and constructive in all interactions.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce** the issue
- **Expected behavior**
- **Actual behavior**
- **Environment details** (PHP version, Laravel version, OS)
- **Error messages or logs**

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear title and description**
- **Use case** for the enhancement
- **Expected benefits**
- **Possible implementation** (if you have ideas)

### Pull Requests

1. **Fork** the repository
2. **Create a branch** for your feature (`git checkout -b feature/amazing-feature`)
3. **Make your changes**
4. **Test your changes** thoroughly
5. **Commit** with clear messages (`git commit -m 'Add amazing feature'`)
6. **Push** to your fork (`git push origin feature/amazing-feature`)
7. **Create a Pull Request**

#### Pull Request Guidelines

- Follow PSR-12 coding standards
- Add tests for new features
- Update documentation
- Keep commits atomic and well-described
- Ensure all tests pass
- Update CHANGELOG.md

## Development Setup

```bash
# Clone the repository
git clone https://github.com/yourvendor/zatca-laravel.git
cd zatca-laravel

# Install dependencies
composer install

# Run tests
composer test

# Check code style
composer cs-check

# Fix code style
composer cs-fix
```

## Coding Standards

- Follow **PSR-12** coding standards
- Use **type hints** for all parameters and return types
- Add **DocBlocks** for all methods
- Write **descriptive variable names**
- Keep methods **focused** and **small**
- Follow **SOLID** principles

## Testing

- Write tests for all new features
- Ensure existing tests pass
- Aim for high test coverage
- Test edge cases

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit --filter TestClassName

# Generate coverage report
vendor/bin/phpunit --coverage-html coverage
```

## Documentation

- Update README.md for major features
- Update USAGE_EXAMPLES.md with code examples
- Add inline comments for complex logic
- Update CHANGELOG.md following Keep a Changelog format

## Version Control

### Commit Messages

Follow conventional commits:

```
feat: add support for new invoice type
fix: resolve QR code generation issue
docs: update installation guide
test: add tests for onboarding
refactor: improve certificate parser
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

### Branching

- `main` - stable releases
- `develop` - active development
- `feature/*` - new features
- `bugfix/*` - bug fixes
- `hotfix/*` - urgent fixes

## Release Process

1. Update version in `composer.json`
2. Update CHANGELOG.md
3. Create git tag
4. Push to GitHub
5. Create GitHub release
6. Packagist auto-updates

## Questions?

Feel free to open an issue for questions or discussions.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
