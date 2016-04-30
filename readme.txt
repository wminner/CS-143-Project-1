Yuanchen Liu & Wesley Minner

CS 143 Database Systems
Professor Carlo Zaniolo
Spring 2016

Spec: http://yellowstone.cs.ucla.edu/cs143/project/project1A.html
	  http://yellowstone.cs.ucla.edu/cs143/project/project1B.html

================================================================================
Project 1
================================================================================

Makefile Documentation
======================
(not applicable for grader as it was not included in submission)

To test 1A functionality, perform the following steps:
1. make sqlrefresh
2. make phptest
3. Use a browser to view "http://localhost:1438/~cs143/"

*If data has already been loaded into MySQL, only need to do 'make phptest',
 then browse to the url above.

To test 1B functionality, perform the following steps:
1. make sqlrefresh
2. make webtest
3. Use a browser to view "http://localhost:1438/~cs143/"

*If data has already been loaded into MySQL, only need to do 'make webtest',
 then browse to the url above.


Project Description
===================

We have met all specifications put forward by the spec. The website is not too
flashy, but it is very consistent, bug free, and cleanly designed. We focused on
providing an easy-to-navigate interface, with convenient links for adding and
viewing content that you may have just updated. There are pages for adding...

* Actors/Directors (I1)
* Movies (I2)
* Movie user reviews (I3)
* Movie/Actor relations (I4)
* Movie/Director relations (I5)

The add movie page allows users to specify multiple genres by delimiting them
with spaces.

There is a single page for searching Actors and Movies by name/title:

* Search Actor/Movie (S1, B1, B2)

You can filter by actors only or movies only (or both by default). The search
field behaves as specified by the spec, allowing spaces to delimit search terms.
Clicking on a search result brings you to that specific movie or actor page for
more detailed information. You can search again from within the movie or actor
page instead of having to navigate back to the dedicated search page.

For movie pages, you can add click through to all the actor pages. You can also
view critic and user reviews. A link is included to quickly submit an user
review. For actor pages, you can click through an actor's roles to get to the 
movie they played that role in.

For each page that inserts/updates the database, a success message will appear
if your submission succeeded, and a link is provided for convenience to
immediately view the updated movie or actor page.


Collaboration
=============

We collaborated by splitting up the pages to make. We each coded roughly half
the pages in the project and used a private git repository (bitbucket) to share
our work. The readme also acted as a TODO list when bugs were found that had
dependencies (so we would remember to fix them later).

For better collaboration, we should focus on proof-reading each other's code
more often to catch errors and resolve misunderstandings earlier. Unfortunately
time is always a constraint and this limits the amount of extra time we have for
proof- reading. (especially during midterm weeks!) Additionally our best work
occurred when we were both actively working on the project at the same time, as
we could communicate through a chat client more immediately, with our thoughts
on the same issues.


TODO List
=========

1A DONE:
* create.sql
* load.sql
* queries.sql
* query.php
* violate.sql

1B DONE:
* AddActorDirector.php
* AddMovieInformation.php
* AddMovieActorRelation.php
* AddMovieDirectorRelation.php
* AddMovieReview.php
* showActorInfo.php
* showMovieInfo.php
* searchActorMovie.php
* index.html
* directory.html


1A TODO:
* N/A

1B TODO:
* Create test cases