# CS 143 Project 1 Makefile
# Maybe not necessary, but at least you can use it to clean old buffers and make
# the distribution reliably

UID = 503392352
# DB = TEST
DB = CS143

DIST_SOURCES = readme.txt team.txt create.sql load.sql queries.sql \
	query.php violate.sql

# Copy and rename index.php from ~/www/ to ~/Project1/ git committing
phpcommit:
	cp ~/www/index.php ~/Project1/query.php

# Copy query.php to ~/www/ for testing using "http://localhost:1438/~cs143/"
phptest:
	cp ~/Project1/query.php ~/www/index.php

create: create.sql
	mysql $(DB) < create.sql

load: load.sql
	mysql $(DB) < load.sql

dist: P1A.zip

P1A.zip: $(DIST_SOURCES)
	rm -rf $(UID)
	mkdir $(UID)
	cp $(DIST_SOURCES) $(UID)
	zip -r $@  $(UID)
	./p1a_test $(UID)

sqlrefresh: sqlclean create load

sqlclean: clean.sql
	mysql $(DB) < clean.sql

clean:
	rm -rf $(UID) *.tmp *.zip *~ *\#