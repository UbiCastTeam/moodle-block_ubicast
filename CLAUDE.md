# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

`block_ubicast` is a Moodle **block** that embeds a UbiCast Nudgis channel (or a
single media) into a course page as an `iframe`. Authentication and per-media
permissions are handled through the OAuth-signed **LTI** launch provided by the
companion `mod_ubicast` activity module — this block does **not** implement LTI
itself, it delegates to `mod_ubicast`.

`mod_ubicast` (<https://github.com/UbiCastTeam/moodle-mod_ubicast>) is a **required
dependency**. Requires **Moodle >= 3.7** and a **Nudgis site >= 8.1** (the
type/order filters additionally require **Nudgis >= 13.0.0**).

## Common Commands

| Task                                       | Command    |
| ------------------------------------------ | ---------- |
| Build the distribution zip for moodle.org  | `make zip` |

There is **no npm/composer, no build step, and no automated test suite** in this
repo. Testing is **manual** in a running Moodle instance (see
[Development](.claude/docs/development.md)). Linting is done with Moodle's external
**Code Checker**, not an in-repo config.

## Language strings

When adding or renaming a UI string, update **both** `lang/en/block_ubicast.php`
and `lang/fr/block_ubicast.php`, and read it via
`get_string(..., 'block_ubicast')`. Never hard-code user-facing text. See
[Conventions](.claude/docs/conventions.md).

## Version bumps

Any behavior change must bump `$plugin->version` in `version.php` (date format
`YYYYMMDDXX`) so Moodle runs the upgrade. This block has **no DB table**; instance
settings are stored in Moodle's standard block instance config. See
[Development](.claude/docs/development.md).

## Additional documentation

- [Architecture](.claude/docs/architecture.md) — how the block renders the iframe, the `lti.php` → `mod_ubicast` delegation, Nudgis object-id prefixes, config fields
- [Conventions](.claude/docs/conventions.md) — PHP (Moodle standards), language strings, capabilities, version discipline
- [Development](.claude/docs/development.md) — dev environment (moodle-docker), install, cookies gotcha, packaging
