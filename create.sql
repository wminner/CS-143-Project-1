-- Not sure about NULLs
CREATE TABLE Movie (
	id INT NOT NULL AUTO_INCREMENT,
	title VARCHAR(100) NOT NULL,
	year INT,
	rating VARCHAR(10),
	company VARCHAR(50),
	PRIMARY KEY (id)
);

CREATE TABLE Actor (
	id INT NOT NULL AUTO_INCREMENT,
	last VARCHAR(20) NOT NULL,
	first VARCHAR(20) NOT NULL,
	sex VARCHAR(6),
	dob DATE NOT NULL,
	dod DATE,
	PRIMARY KEY (id)
);

-- Not sure about primary key or NULLs
-- mid itself should be enough since there is no other column in the table that can differetiate two tuples with different income/tickets for a given movie
CREATE TABLE Sales (
	mid INT NOT NULL,
	ticketsSold INT NOT NULL,
	totalIncome INT,
	PRIMARY KEY (mid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;


CREATE TABLE Director (
	id INT NOT NULL AUTO_INCREMENT,
	last VARCHAR(20) NOT NULL,
	first VARCHAR(20) NOT NULL,
	dob DATE NOT NULL, 
	dod DATE,
	PRIMARY KEY (id)
);

CREATE TABLE MovieGenre (
	mid INT NOT NULL,
	genre VARCHAR(20) NOT NULL,
	PRIMARY KEY (mid, genre),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

CREATE TABLE MovieDirector (
	mid INT NOT NULL,
	did INT NOT NULL,
	PRIMARY KEY (mid, did),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE,
	FOREIGN KEY (did) REFERENCES Director(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

CREATE TABLE MovieActor (
	mid INT NOT NULL,
	aid INT NOT NULL,
	role VARCHAR(50),
	PRIMARY KEY (mid, aid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE,
	FOREIGN KEY (aid) REFERENCES Actor(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

-- Not sure about primary key or NULLs
-- Either of imdb or rot can be missing for a giving movie rating. We may be able to ensure constaints during data ingest
CREATE TABLE MovieRating (
	mid INT NOT NULL,
	imdb INT,
	rot INT,
	PRIMARY KEY (mid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

-- Not sure about primary key or NULLs
-- I assume a user can post review for a given movie multiple times.
CREATE TABLE Review (
	name VARCHAR(20) NOT NULL,
	time TIMESTAMP NOT NULL, 
	mid INT NOT NULL,
	rating INT,
	comment VARCHAR(500),
	PRIMARY KEY (name, time, mid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

CREATE TABLE MaxPersonID (
	id INT NOT NULL,
	PRIMARY KEY (id));

CREATE TABLE MaxMovieID (
	id INT NOT NULL,
	PRIMARY KEY (id));