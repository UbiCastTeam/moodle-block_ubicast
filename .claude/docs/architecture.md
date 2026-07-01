# Architecture

## Role

`block_ubicast` is a standard Moodle **block** that embeds a Nudgis channel (or
media) into a course page as an `iframe`. It is a thin UI layer on top of the LTI
launch machinery provided by the `mod_ubicast` activity module — the block builds a
URL and delegates the actual signed LTI launch to `mod_ubicast`.

## Key files

| File | Responsibility |
| --- | --- |
| `version.php` | Version, `requires` (Moodle 3.7), `mod_ubicast` dependency |
| `block_ubicast.php` | The `block_ubicast extends block_base` class: `init()`, `specialization()`, `applicable_formats()`, and `get_content()` which renders the iframe |
| `edit_form.php` | `block_ubicast_edit_form extends block_edit_form`: the per-instance config form (title, height, types, order, resource id) |
| `lti.php` | The iframe target endpoint; capability check then delegates to `ubicast_launch_tool()` |
| `db/access.php` | Capabilities (`block/ubicast:addinstance`) |
| `classes/privacy/provider.php` | `null_provider` — stores no personal data |
| `lang/{en,fr}/block_ubicast.php` | UI strings (kept in sync) |
| `pix/icon.svg` | Block icon |

There is **no** `settings.php` (no site-wide admin settings), **no** `db/install.xml`
(no custom table), and **no** frontend JS/AMD in this plugin.

## Render + launch flow

1. `block_ubicast::get_content()` (`block_ubicast.php`) returns an `iframe` whose
   `src` points at this block's own `lti.php`:
   `.../blocks/ubicast/lti.php?id=<courseid>&oid=<resourceid>` plus optional
   `&filters=<url-encoded JSON>` and `&orderBy=<field>`. Content only renders when
   the user is logged in and `config->resourceid` is set; otherwise the block shows
   the `unconfigured_message` string.
2. `lti.php` reads `id`/`oid`/`orderBy`/`filters`, does `require_login($course)` and
   `require_capability('mod/ubicast:view', $context)` (the `mod_ubicast` capability —
   this block defines no `view` capability of its own), then calls:
   ```php
   ubicast_launch_tool($course, null, $oid . '/?newtab' . $querystring);
   ```
3. `ubicast_launch_tool()` lives in `mod/ubicast/locallib.php` (pulled in via the
   two `require_once` on `mod/ubicast/lib.php` + `locallib.php`). It builds the
   OAuth-signed, self-submitting LTI form that POSTs to the Nudgis site. All LTI
   signing and the Nudgis URL / key / secret config belong to `mod_ubicast`, not
   here.

## Nudgis object-id prefixes

`config->resourceid` (aka `oid`) is a Nudgis object id whose **first character
encodes the type**, followed by 19 lowercase alphanumerics (validated as
`[cvlp][a-z0-9]{19}`, e.g. `c124cbdfb0e5c9e28a30`):

- `c` → channel
- `v` → video
- `l` → live
- `p` → photos

`edit_form.php` lets the user paste a full Nudgis URL into the resource-id field; an
inline `onchange` regex (`(?:^|/)([cvlp][a-z0-9]{19})...`) extracts just the id.

## Config fields (per block instance)

Defined in `edit_form.php`, stored in the block's instance config (no DB table):

| Field | Type | Default | Notes |
| --- | --- | --- | --- |
| `config_title` | text | `pluginname` string | Block heading (applied in `specialization()`) |
| `config_height` | int | `400` | iframe height in px |
| `config_types` | multi-select | all of `c,v,l,p` | Sent as `filters={"itemType":[...]}`; requires Nudgis >= 13.0.0 |
| `config_orderby` | select | `-creation_date` | Sent as `orderBy`; requires Nudgis >= 13.0.0 |
| `config_resourceid` | text (`PARAM_ALPHANUMEXT`) | — | The Nudgis object id; required for the block to render |

`instance_allow_multiple()` and `instance_allow_config()` are both `true`;
`applicable_formats()` allows the block everywhere except the Dashboard (`my`).
