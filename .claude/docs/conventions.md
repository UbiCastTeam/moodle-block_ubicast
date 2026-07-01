# Conventions

Follow the existing code — this plugin matches standard Moodle block conventions.
Do **not** introduce a new style.

## PHP / Moodle standards

- Every PHP file starts with the **GPL v3 header**. Files that are not entry points
  (`edit_form.php`, `version.php`, `db/access.php`, `classes/…`) also add
  `defined('MOODLE_INTERNAL') || die();`. Entry points that begin with
  `require_once('../../config.php')` (i.e. `lti.php`) do not.
- Use the Moodle APIs, not raw PHP: `get_string()` for text, the `$DB` global for
  database access, Moodle **contexts** and **capabilities** for access control
  (`require_login()`, `require_capability()`).
- Every file carries the `@package block_ubicast` / `@copyright … UbiCast` /
  `@license … GNU GPL v3` docblock.
- Autoloaded classes live under `classes/` with the `block_ubicast\…` namespace
  (currently just `block_ubicast\privacy\provider`).
- Match the surrounding style — the codebase is **not type-hinted**; keep it
  consistent rather than adding types to only some functions.

## Language strings

- Keep `lang/en/block_ubicast.php` and `lang/fr/block_ubicast.php` **in sync** —
  add/rename a key in both. Keys are kept **alphabetically sorted**.
- Never hard-code user-facing text; always go through
  `get_string(..., 'block_ubicast')`.

## Capabilities

Defined in `db/access.php`:

- `block/ubicast:addinstance` (write, `CONTEXT_BLOCK`, `RISK_SPAM | RISK_XSS`,
  allowed for `editingteacher` + `manager`, clones from
  `moodle/site:manageblocks`).

The block has **no `view` capability of its own** — `lti.php` intentionally reuses
`mod/ubicast:view` from the dependency plugin.

## Version discipline

Bump `$plugin->version` in `version.php` (format `YYYYMMDDXX`) whenever you change
plugin behavior, so Moodle runs the upgrade. Keep `$plugin->dependencies` (on
`mod_ubicast`) accurate if you rely on a newer `ubicast_launch_tool()` signature or
Nudgis feature.

## Linting

There is no in-repo linter config (no `.phpcs.xml`, no `.eslintrc`). Validate PHP
against the Moodle **Code Checker** plugin / Moodle coding standards.
