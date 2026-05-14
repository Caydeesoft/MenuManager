# Release Notes

This release is generated when a version tag is pushed.

## Before Tagging

- Update `CHANGELOG.md` with user-facing changes.
- Run `composer test`.
- Create a semantic version tag, for example `v1.0.0`.

## Tagging

```bash
git tag v1.0.0
git push origin v1.0.0
```

GitHub Actions will run the test suite and create a GitHub release with
auto-generated release notes.
