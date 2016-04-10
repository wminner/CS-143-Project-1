# CS 143 Project 1 Makefile
# Maybe not necessary, but at least you can use it to clean old buffers and make
# the distribution reliably

UID = 703549234

DIST_SOURCES = readme.txt team.txt create.sql load.sql queries.sql \
	query.php violate.sql

create: create.sql
	mysql TEST < create.sql

load: load.sql
	mysql TEST < load.sql

dist: P1A.zip

P1A.zip: $(DIST_SOURCES)
	rm -rf $(UID)
	mkdir $(UID)
	cp $(DIST_SOURCES) $(UID)
	zip -r $@  $(UID)
	./p1a_test $(UID)

sqlclean: clean.sql
	mysql TEST < clean.sql

clean:
	rm -rf $(UID) *.tmp *.zip *~ *\#