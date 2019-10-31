Demo of the Drupal Purge Module
=====

This repo serves to demo the Drupal 8 [Purge](https://www.drupal.org/project/purge) module with cache tags.

Start the project
-----

    ./scripts/deploy

Then visit http://0.0.0.0:7003/selftest.php

You should see four boxes representing the database, Drupal, Varnish and a dummy PHP client which consumes data from Varnish.

The first box (the database) should be green (meaning that it is cached or that caching is not applicable); the other three boxes (Drupal, Varnish and the Client) should be orange, meaning they are displaying the right data, but not cached.

Refresh http://0.0.0.0:7003/selftest.php and now all boxes should be green, meaming they all display the correct data and are cached.

About this project
-----

This project represents a dummy simple architecture to demonstrate how cache tags combined with a very long (1-year) cache time to live, combined with a front-end cache like Varnish, can speed up a site and make out-of-date caches a thing of the past.

This dummy project is designed to, given a user ID, display its username. User 5 is taken as an example (it is already present in the starter database once you run ./scripts/deploy.sh):

* Drupal exposes an API at http://0.0.0.0:7001/username/5
* Varnish exists in front of Drupal at http://0.0.0.0:7002/username/5
* A dummy PHP frontend displays the username at http://0.0.0.0:7003/username/5

Every level performs its own caching, with the following principles:

* Caching is tag-based and not URL-based. For example user 5's username has a number of cache tags, the most important one being "user:5". Any time Drupal considers that representations of user 5 should have their cache invalidated, the tag "user:5" is invalidated. This is part of Drupal core.
* Caching can be forever (you can set the time to live to year, as is done at ./drupal/config/system.performance.yml). See also ./drupal/custom-modules/my_custom_module/src/Controller/MyController.php on how the JSON API is cached, and, for example for user 5, is defined as having the cache tag "user:5" (along with a dummy tag, "some-custom-application-tag", which is not actually used, but, if your applicaiton had some custom cache invalidation logic, you can use custom cache tags).
* Drupal exposes its cache tags in a custom header, to let external systems such as Varnish know which cache tag invalidations should invalidate which content. To see this in action, call `curl -I http://0.0.0.0:7001/username/5 | grep Purge-Cache-Tags`; this functinality is managed by enabling the **purge_purger_http_tagsheader** module which is part of [purge_purger_http](https://drupal.org/project/purge_purger_http).
* The [Purge](https://drupal.org/project/purge) module is used to manage telling external systems when cache tags need invlidation. It does nothing on its own; you need to also [Varnish Purger](https://www.drupal.org/project/varnish_purge) (be careful, the project is called varnish_purge but the module is called varnish_purger with an "r") to purge Varnish, and [purge_purger_http](https://www.drupal.org/project/purge_purger_http) to purge our dummy frontend by calling http://0.0.0.0:7003/clearcache.php.
* Varnish, at ./varnish/default.vcl, has a specific configuration written in the "Varnish Configuration Language", which is necessary for this to work. This was adapted from the example VCL linked to from the blog post [Purge cache-tags with Varnish by Mikke Schirén, 2016-07-09, Digitalist Tech](https://digitalist-tech.se/blogg/purge-cachetags-varnish).
* Purge and its plugins are configured at http://0.0.0.0:7001/admin/config/development/performance/purge (get a login link to Drupal by running ./scripts/uli.sh)

To see this work
-----

* Visit http://0.0.0.0:7003/selftest.php and make sure all boxes are green (reload the page if you see orange boxes).
* Each box should show something like "Real Username: 177" which means the username of user 5 is 177 (the number can vary for you).
* Now let's modify 5's username. You can do so by cliking in the backend GUI, or by running `./scripts/changeusername.sh`.
* Reload http://0.0.0.0:7003/selftest.php, the first box representing the database should be green; other boxes should be orange, which means they are not cached, which is normal because changing the username cleared the tag "user:5" and that was propagated to Varnish and our dummy frontend, which are now re-fetching the username and re-caching it.
* Reload http://0.0.0.0:7003/selftest.php; now everything is cached again and the data is correct.
