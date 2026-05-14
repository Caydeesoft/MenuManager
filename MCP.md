# MCP Compatibility

This package is a Laravel Composer package, not a Model Context Protocol server.

The official MCP Registry stores metadata for MCP servers and currently supports
server packages distributed through supported runtime/package formats such as
npm, PyPI, NuGet, Docker/OCI, MCPB, and remote HTTP servers.

Because this package does not expose MCP tools, resources, prompts, or an MCP
transport, it should not publish an MCP `server.json` manifest. Adding one would
misrepresent the package to MCP clients.

## AI/Agent-Friendly Project Metadata

For coding agents and MCP-capable development environments, this repository
provides:

- `README.md` for installation and usage.
- `CHANGELOG.md` for release history.
- `RELEASE_NOTES.md` for tag-release workflow documentation.
- PHPUnit tests under `tests/`.

If this package later adds a real MCP server, add a standards-compliant
`server.json` only after the server exposes a valid MCP transport.
