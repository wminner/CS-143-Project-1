Yuanchen Liu & Wesley Minner

CS 143 Database Systems
Professor Carlo Zaniolo
Spring 2016

Spec: http://yellowstone.cs.ucla.edu/cs143/project/project1A.html
	  http://yellowstone.cs.ucla.edu/cs143/project/project1B.html

================================================================================
Project 1
================================================================================

To test functionality, perform the following steps:
1. make sqlrefresh
2. make phptest
3. Use a browser to view "http://localhost:1438/~cs143/"

*If data has already been loaded into MySQL, only need to do 'make phptest',
 then browse to the url above.

If 'make sqlrefesh' is giving errors try:
1. make sqlclean
2. make create
3. make load


DONE:
* create.sql
* load.sql
* queries.sql
* query.php
* violate.sql

TODO:
* AddActorDirector.php
* AddMovieInformation.php
* AddMovieActorRelation.php
* AddMovieDirectorRelation.php
* showActorInfo.php
* showMovieInfo.php
* searchActorMovie.php

* Change $desired_db from TEST to CS143
* Remove all prints of raw sql queries
* Fix showActor page so it doesn't show empty table if you clicked on directory listing