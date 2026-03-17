# Utility Scripts

This directory contains operational and development helper scripts for the KazUTB Smart Library project.

Planned script groups:

- db/ — database setup, reset, and seeding
- env/ — environment validation
- quality/ — lint, formatting, and static checks
- migration/ — helper wrappers around migration pipeline commands

No production credentials are stored in scripts. All scripts must read values from environment variables.
