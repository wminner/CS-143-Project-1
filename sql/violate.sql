-- Primary key constraints 
-- =============================================================================
-- 1. The movie ID (mid) needs to be the primary key as
-- each movie should only have one entry. Movie id = 2 is an existing movie in the Movie table. 

INSERT INTO Movie 
	VALUES (2, 'INVALID', 2016, 'G','Sony');
-- MySQL response:
-- ERROR 1062 (23000): Duplicate entry '2' for key 'PRIMARY'

-- 2. The actor ID (id) needs to be the primary key as each actor should only
-- have one entry. Actor id = 1 is an existing actor in Actor table. 

INSERT INTO Actor
	VALUES (1, 'Smith', 'Actor', 'M', '1969-10-16', NULL);
-- MySQL response:
-- ERROR 1062 (23000): Duplicate entry '1' for key 'PRIMARY'

-- 3. The director ID (id) needs to be the primary key as each director should
-- only have one entry. Director id = 16 is an existing director in the Director table.

INSERT INTO Director
	VALUES (16, 'Smith', 'Director', '1969-10-16', NULL);
-- MySQL response:
-- ERROR 1062 (23000): Duplicate entry '16' for key 'PRIMARY'


-- Referential integrity constraints 
-- =============================================================================
-- 1. Sales.mid references Movie.id since the
-- sales record is only relevent with respect to an existing movie

INSERT INTO Sales
	VALUES ((SELECT MAX(id) +1 FROM Movie), 100, 100);
-- MySQL response:
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`Sales`, CONSTRAINT `Sales_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`) ON DELETE CASCADE)

-- 2. MovieGenre.mid references Movie.id since genre is only applicable to an
-- existing movie

INSERT INTO MovieGenre
	VALUES ((SELECT MAX(id) +1 FROM Movie), 'Horror');
-- MySQL response:
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieGenre`, CONSTRAINT `MovieGenre_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`) ON DELETE CASCADE)

-- 3. MovieDirector.mid references Movie.id since movie director association is
-- only applicable to an existing movie. MAX(Movie.id) + 1 doens't exist. 

INSERT INTO MovieDirector
	VALUES ((SELECT MAX(id) +1 FROM Movie), (SELECT MAX(id) FROM Director));
-- MySQL response:
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`) ON DELETE CASCADE)

-- 4. MovieDirector.did references Director.id since movie director association
-- is only applicable to a director that already exist. MAX(Director.id) + 1
-- doesn't exist.

INSERT INTO MovieDirector
	VALUES ((SELECT MAX(id) FROM Movie), (SELECT MAX(id) +1 FROM Director));
-- MySQL response:
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_2` FOREIGN KEY (`did`) REFERENCES `Director` (`id`) ON DELETE CASCADE)

-- 5. MovieActor.mid references Movie.id since movie actor association is only
-- applicable to an existing movie

INSERT INTO MovieActor
	VALUES((SELECT MAX(id) +1 FROM Movie), (SELECT MAX(id) FROM Actor), 'Lead');
-- MySQL response:
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieActor`, CONSTRAINT `MovieActor_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`) ON DELETE CASCADE)

-- 6. MovieActor.aid references Actor.id since movie actor association is only
-- applicable to an existing actor

INSERT INTO MovieActor
	VALUES((SELECT MAX(id) FROM Movie), (SELECT MAX(id) +1 FROM Actor), 'Lead');
-- MySQL response:
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieActor`, CONSTRAINT `MovieActor_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `Actor` (`id`) ON DELETE CASCADE)


-- CHECK constraints
-- =============================================================================
-- 1. IMDB Rating (imdb) should be between 0 and 100

UPDATE MovieRating 
	SET imdb = 101 
	WHERE mid = 1000;

-- 2. RottenMTomato Rating (rot) should be between 0 and 100

UPDATE MovieRating
	SET rot = 101
	WHERE mid = 1000;

-- 3. Rating should be between 1 and 5

INSERT INTO Review
	VALUES('Lui', '2016-04-14 00:00:00', (SELECT MAX(id) FROM Movie), 6, 'Hello World');

-- ***MySQL responses not listed here because MySQL does not support CHECK 
-- constraints. As a result these statements work correctly even though they
-- should fail***