-- Query 1
-- Names of all the actors in the movie "Die Another Day"
-- format: <firstname> <lastname>
SELECT CONCAT(A.first, ' ', A.last) AS "Actors in 'Die Another Day'"
FROM Actor AS A
WHERE A.id = ANY (SELECT MA.aid
                  FROM MovieActor AS MA
                  WHERE MA.mid = (SELECT M.id
                                  FROM Movie AS M
                                  WHERE M.title = "Die Another Day"));

-- Query 2
-- Count of all actors who acted in multiple movies
SELECT COUNT(aid) AS "Actors who acted in multiple movies"
FROM (SELECT MA.aid, COUNT(mid) AS num_movies
      FROM MovieActor AS MA
      GROUP BY MA.aid) AS num_acted
WHERE num_acted.num_movies >= 2;


-- Query 3
-- Title of movies that sell more than 1,000,000 tickets
SELECT M.title AS "Movies that sold more than 1,000,000 tickets"
FROM Movie AS M
WHERE M.id = ANY (SELECT S.mid
                  FROM Sales AS S
                  WHERE S.ticketsSold > 1000000);

-- Query 4
-- Directors of movies that have an IMDB and Rotten Tomato rating at least 90
SELECT CONCAT(D.last, ', ', D.first) AS "Directors of movies with IMDB and Rotten Tomatoes rating >= 90"
FROM Director AS D
WHERE D.id = ANY (SELECT DISTINCT MD.did
                  FROM MovieDirector AS MD
                  WHERE MD.mid = ANY (SELECT MR.mid
                  	                  FROM MovieRating as MR
                  	                  WHERE MR.imdb >= 90
                  	                  AND MR.rot >= 90));

-- Query 5
-- Average total income per movies genre, and number of movies in each genre (for additional context)
SELECT MG.genre AS "Genre", AVG(S.totalIncome) AS "Average Total Income (USD)", COUNT(S.totalIncome) AS "Number of Movies"
FROM MovieGenre AS MG
INNER JOIN Sales AS S
ON MG.mid = S.mid
GROUP BY MG.genre;