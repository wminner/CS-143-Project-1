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
CREATE TABLE Sales (
	mid INT NOT NULL,
	ticketsSold INT NOT NULL,
	totalIncome INT,
	PRIMARY KEY (mid, ticketsSold),
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
CREATE TABLE MovieRating (
	mid INT NOT NULL,
	imdb INT NOT NULL,
	rot INT,
	PRIMARY KEY (mid, imdb),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

-- Not sure about primary key or NULLs
CREATE TABLE Review (
	name VARCHAR(20) NOT NULL,
	time TIMESTAMP,
	mid INT NOT NULL,
	rating INT,
	comment VARCHAR(500),
	PRIMARY KEY (name, mid),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON DELETE CASCADE
)	ENGINE = InnoDB;

CREATE TABLE MaxPersonID (
	id INT NOT NULL,
	PRIMARY KEY (id));

CREATE TABLE MaxMovieID (
	id INT NOT NULL,
	PRIMARY KEY (id));