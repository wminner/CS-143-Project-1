-- Query 1
-- Names of all the actors in the movie "Die Another Day"
-- format: <firstname> <lastname>
SELECT CONCAT(a.first,' ',a.last) AS "Actors in 'Die Another Day'"
FROM Movie AS m, MovieActor AS ma, Actor AS a
WHERE m.id = ma.mid
AND a.id = ma.aid
AND title = 'Die Another Day';

-- Query 2
-- Count of all actors who acted in multiple movies
SELECT COUNT(aid) AS "Actors who acted in multiple movies"
FROM (SELECT MA.aid, COUNT(mid) AS num_movies
      FROM MovieActor AS MA
      GROUP BY MA.aid) AS num_acted
WHERE num_acted.num_movies >= 2;


-- Query 3
-- Title of movies that sell more than 1,000,000 tickets
SELECT "Movies that sold more than 1,000,000 tickets"
FROM Movie AS m, Sales AS s
WHERE m.id = s.mid
AND ticketsSold > 1000000;

-- Query 4
-- Directors of movies that have an IMDB and Rotten Tomato rating at least 90
SELECT CONCAT (d.first, ' ', d.last) As "Directors of movies with IMDB and Rotten Tomatoes rating >= 90"
FROM MovieRating AS mr, MovieDirector AS md, Director AS d
WHERE mr.mid = md.mid
AND md.did = d.id
AND mr.imdb >= 90
AND mr.rot >= 90;

-- Query 5
-- Average total income per movies genre, and number of movies in each genre (for additional context)
SELECT MG.genre AS "Genre", AVG(S.totalIncome) AS "Average Total Income (USD)", COUNT(S.totalIncome) AS "Number of Movies"
FROM MovieGenre AS MG
INNER JOIN Sales AS S
ON MG.mid = S.mid
GROUP BY MG.genre;