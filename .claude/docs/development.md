# Development

## Dev environment

Set up a local Moodle with [`moodle-docker`](https://github.com/moodlehq/moodle-docker):

1. Clone `moodle-docker`, the Moodle source
   (<https://download.moodle.org/> or the `moodle/moodle` repo), this repo, and its
   dependency `moodle-mod_ubicast`.
2. Follow the `moodle-docker` README to bring up Moodle.
3. Install both plugins (below). Inside Moodle this block lives at `blocks/ubicast`,
   so you can edit it there directly.

## Install

Install the dependency first (`mod_ubicast` into `moodle/mod` as `ubicast`), then
clone this repo into Moodle's `blocks` directory **as `ubicast`**, then run the
Moodle upgrade:

```bash
cd moodle/blocks
git clone https://github.com/UbiCastTeam/moodle-block_ubicast ubicast
```

Then visit Moodle as an administrator and complete the upgrade from the Dashboard.
There are no site-wide admin settings for this block — the Nudgis URL and LTI
key/secret are configured on the **`mod_ubicast`** plugin. To use the block, turn
editing on in a course, add the "Nudgis media list" block, and configure it (title,
height, content types, order, and the Nudgis channel/object id).

## Cookies gotcha

LTI auth needs Nudgis cookies. If Nudgis is on a completely different domain than
Moodle, browsers block them as third-party cookies. Host Nudgis on a **sibling
subdomain** of Moodle (e.g. Moodle `moodle.example.com` → Nudgis
`nudgis.example.com`), or allow third-party cookies in the browser.

## Packaging

```bash
make zip   # git archive HEAD -> block_ubicast.zip, prefixed "ubicast/"
```

Produces the zip uploaded to the moodle.org plugin repository.

## Testing

There is **no automated test suite and no CI** in this repo. Test changes
**manually** in a running Moodle instance: add the block to a course, set a Nudgis
channel id, and verify the iframe launches and shows the channel for both teacher
and student roles. When touching the type/order filters, confirm against a Nudgis
site **>= 13.0.0** (older sites ignore them).
