# Security Policy

## Supported Versions

We actively support and provide security updates for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x.x   | :white_check_mark: |

## Reporting a Vulnerability

We take security vulnerabilities seriously. If you discover a security vulnerability, please follow these steps:

1. **Do not** open a public GitHub issue
2. Email security details to: **security@relaywarden.com**
3. Include:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact
   - Suggested fix (if any)

### What to Expect

- You will receive an acknowledgment within 48 hours
- We will provide an initial assessment within 7 days
- We will keep you informed of our progress
- We will notify you when the vulnerability is fixed

### Disclosure Policy

- We will work with you to understand and resolve the issue quickly
- We will credit you for the discovery (unless you prefer to remain anonymous)
- We will not take legal action against security researchers acting in good faith
- We ask that you:
  - Allow us a reasonable time to fix the issue before public disclosure
  - Not access or modify user data without permission
  - Not perform any actions that could harm our users or services
  - Not disclose the vulnerability publicly until we've released a fix

## Security Best Practices

When using the SDK:

- **Never commit API tokens or credentials** to version control
- Use environment variables or secure credential storage
- Keep the SDK updated to the latest version
- Use HTTPS for all API communications
- Validate and sanitize all user inputs
- Implement proper error handling

## Known Security Considerations

- The SDK uses Bearer token authentication - protect your tokens
- API tokens should be rotated regularly
- Use project-scoped tokens when possible for least privilege access
- Monitor API usage for suspicious activity

## Security Updates

Security updates will be released as patch versions (e.g., 1.0.1, 1.0.2). We recommend:

- Keeping the SDK updated via Composer
- Reviewing release notes for security-related changes
- Subscribing to security advisories

Thank you for helping keep RelayWarden and our users safe!
