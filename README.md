UbiCast MediaServer block plugin
================================

Copyright: UbiCast (https://www.ubicast.eu)
License: https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later


Description:
------------

This block plugin allows teachers to add MediaServer channel in courses as a block.


Dependencies:
-------------

* The UbiCast base plugin: `mod_ubicast` (https://github.com/UbiCastTeam/moodle-mod_ubicast).
* A UbiCast MediaServer web site (version >= *8.1*). Visit https://www.ubicast.eu/en/solutions/delivery/ to ask for a trial.
* Cookies must be allowed for the MediaServer web site (see note below).


Important note about cookies:
-----------------------------

In order to make the LTI authentication work, MediaServer needs cookies usage.
If your MediaServer is using a domain totally different from your Moodle domain, cookies will probably get blocked by browsers because they will be classified as third party cookies.
To avoid MediaServer cookies to be considered as third party cookies, we recommend to use a sub domain for MediaServer using the same top domain as the Moodle site (for example, if your Moodle uses `moodle.yourdomain.com` as domain, you can use `mediaserver.yourdomain.com` as MediaServer domain).
It is also possible to allow third party cookies usage in the browser settings.


Installation:
-------------

* In the Moodle server, go to the Moodle atto plugins directory: `cd blocks`
* Clone the repository: `git clone https://github.com/UbiCastTeam/moodle-block_ubicast.git ubicast`
* Visit Moodle as administrator
* Navigate to Dashboard (upgrade your Moodle)


How does it work?
-----------------

This plugin relies heavily on the https://github.com/UbiCastTeam/moodle-mod_ubicast plugin's LTI integration mechanism that you can read on in said README.
