
-- Primary Key Constraint: The movie ID (mid) needs to be the primary key as each movie should only have one entry
CREATE TABLE Movie (
	id INT NOT NULL,
	title VARCHAR(100) NOT NULL,
	year INT,
	rating VARCHAR(10),
	company VARCHAR(50),
	PRIMARY KEY (id)
);

-- Primary Key Constraint: The actor ID (id) needs to be the primary key as each actor should only have one entry
-- Check Constraint: The date of birth should either be null or preceed Date of Death
CREATE TABLE Actor (
	id INT NOT NULL,
	last VARCHAR(20) NOT NULL,
	first VARCHAR(20) NOT NULL,
	sex VARCHAR(6),
	dob DATE NOT NULL,
	dod DATE,
	PRIMARY KEY (id),
	CHECK(dod IS NULL OR dob < dod)
);

-- Referential Integrity Constraint: Sales.mid references Movie.id since the sales record is only relevent with respect to an existing movie
CREATE TABLE Sales (
	mid INT NOT NULL,
	ticketsSold INT NOT NULL,
	totalIncome INT,
	PRIMARY KEY (mid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

-- Primary Key Constraint: The director ID (id) needs to be the primary key as each director should only have one entry
-- Check Constraint: The Date of Birth should either be null or preceed 
CREATE TABLE Director (
	id INT NOT NULL,
	last VARCHAR(20) NOT NULL,
	first VARCHAR(20) NOT NULL,
	dob DATE NOT NULL, 
	dod DATE,
	PRIMARY KEY (id),
	CHECK(dod IS NULL OR dob < dod)
);

-- Referential Integrity Constraint: MovieGenre.mid references Movie.id since genre is only applicable to an existing movie
CREATE TABLE MovieGenre (
	mid INT NOT NULL,
	genre VARCHAR(20) NOT NULL,
	PRIMARY KEY (mid, genre),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

-- Referential Integrity Constraint: MovieDirector.mid references Movie.id since movie director association is only applicable to an existing movie
-- Referential Integrity Constraint: MovieDirector.did references Director.id since movie director association is only applicable to a director that already exist
CREATE TABLE MovieDirector (
	mid INT NOT NULL,
	did INT NOT NULL,
	PRIMARY KEY (mid, did),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE,
	FOREIGN KEY (did) REFERENCES Director(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

-- Referential Integrity Constraint: MovieActor.mid references Movie.id since movie actor association is only applicable to an existing movie
-- Referential Integrity Constraint: MovieActor.aid references Actor.id since movie actor association is only applicable to an existing actor
CREATE TABLE MovieActor (
	mid INT NOT NULL,
	aid INT NOT NULL,
	role VARCHAR(50),
	PRIMARY KEY (mid, aid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE,
	FOREIGN KEY (aid) REFERENCES Actor(id) ON DELETE CASCADE
)	ENGINE = InnoDB;


-- Check Constraint: Either imdb or rot should exist
-- Check Constraint: IMDB Rating (imdb) should be either Null or between 0 and 100
-- Check Constraint: Rotten Tomato Rating (rot) should be either Null or between 0 and 100
CREATE TABLE MovieRating (
	mid INT NOT NULL,
	imdb INT,
	rot INT,
	PRIMARY KEY (mid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE,
	CHECK(imdb IS NOT NULL OR rot IS NOT NULL),
	CHECK(imdb IS NULL OR (imdb <= 100 AND imdb >= 0)),
	CHECK(rot IS NULL OR (rot <= 100 AND rot >= 0))
)	ENGINE = InnoDB;

-- Check Constraint: Rating should be between 1 and 5
CREATE TABLE Review (
	name VARCHAR(20) NOT NULL,
	time TIMESTAMP NOT NULL, 
	mid INT NOT NULL,
	rating INT,
	comment VARCHAR(500),
	PRIMARY KEY (name, time, mid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE,
	CHECK(rating <= 5 AND rating >= 1)
)	ENGINE = InnoDB;

CREATE TABLE MaxPersonID (
	id INT NOT NULL,
	PRIMARY KEY (id));

CREATE TABLE MaxMovieID (
	id INT NOT NULL,
	PRIMARY KEY (id));