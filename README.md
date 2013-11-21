unl_person
==========

Creates a unl_people remote entity, which gets its data from directory.unl.edu and itegrates with entityreference autocomplete.

Features
--------
* Add a unl_person entity
* Retrieves unl_person entity data from directory.unl.edu via APIs
* Integrates with entityreference for autocomplete support.


Using Entity Reference
----------------------
When setting up an entityreference field that uses unl_person, there are a few things to keep in mind.

1. Target Type = `UNL Person`
2. Entity Selection mode = `UNL Person`
3. Additional Behavior = check `UNL Person behavior`

The `UNL Person` Entity Selection Mode tells entityselection to use our custom selection class instead of the normal sql selection mode.  This will query the directory via an API.

The `UNL Person behavior` will edit the schema of the field to allow the target_id to be varchar.

View Modes
----------
Currently `unl_person` only supports the teaser view mode.
