# CS 143 Project 1 Makefile
# Maybe not necessary, but at least you can use it to clean old buffers and make
# the distribution reliably

UID = 503392352
DB = TEST
# DB = CS143

1A_DIST_SOURCES = readme.txt team.txt sql/create.sql sql/load.sql sql/queries.sql \
	sql/query.php sql/violate.sql

1B_DIST_SOURCES = readme.txt team.txt sql www testcase

# Copy and rename index.php from ~/www/ to ~/Project1/ for git committing
phpcommit:
	cp ~/www/index.php ~/Project1/query.php

# Copy query.php to ~/www/ for testing using "http://localhost:1438/~cs143/"
phptest:
	cp ~/Project1/query.php ~/www/index.php

# Copy website files to git repo for commiting
webcommit:
	cp ~/www/* ~/Project1/www/

# Copy website files from git repo to ~/www/ for testing
webtest:
	cp ~/Project1/www/* ~/www/

create: sql/create.sql
	mysql $(DB) < sql/create.sql

load: sql/load.sql
	mysql $(DB) < sql/load.sql

dist1a: P1A.zip

P1A.zip: $(1A_DIST_SOURCES)
	rm -rf $(UID)
	mkdir $(UID)
	cp $(1A_DIST_SOURCES) $(UID)
	zip -r $@  $(UID)
	./p1a_test $(UID)

dist1b: P1B.zip

P1B.zip: $(1B_DIST_SOURCES)
	rm -rf $(UID)
	mkdir $(UID)
	cp -r $(1B_DIST_SOURCES) $(UID)
	# Remove 1A files from sql directory
	rm $(UID)/sql/clean.sql $(UID)/sql/queries.sql $(UID)/sql/violate.sql
	zip -r $@ $(UID)
	./p1b_test $(UID)

sqlrefresh: sqlclean create load

sqlclean: sql/clean.sql
	mysql $(DB) < sql/clean.sql

clean:
	rm -rf $(UID) *.tmp *.zip *~ *\#
