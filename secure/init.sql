-- All images inputted in the database are from https://film-grab.com/

-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- TODO: create tables

CREATE TABLE movies (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    movie_title TEXT NOT NULL,
    file_ext TEXT NOT NULL,
    release_date TEXT NOT NULL,
    trailer_link TEXT NOT NULL,
    director_name TEXT,
    genre TEXT,
    favorited INTEGER -- 1 for true and null for false
);

CREATE TABLE actors (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	actor_name TEXT NOT NULL UNIQUE
);

CREATE TABLE movie_actors (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	movies_id INTEGER NOT NULL,
    actors_id INTEGER NOT NULL,
    UNIQUE(movies_id, actors_id)
    );

--actors
INSERT INTO actors(id, actor_name) VALUES (1, 'Mark Hamill');
INSERT INTO actors(id, actor_name) VALUES (2, 'Harrison Ford');
INSERT INTO actors(id, actor_name) VALUES (3, 'Carrie Fisher');
INSERT INTO actors(id, actor_name) VALUES (4, 'Emile Hirsch');
INSERT INTO actors(id, actor_name) VALUES (5, 'Kyle MacLachlan');
INSERT INTO actors(id, actor_name) VALUES (6, 'Tom Hardy');
INSERT INTO actors(id, actor_name) VALUES (7, 'Charlize Theron');
INSERT INTO actors(id, actor_name) VALUES (8, 'Ryan Gosling');
INSERT INTO actors(id, actor_name) VALUES (9, 'Elijah Wood');
INSERT INTO actors(id, actor_name) VALUES (10, 'Viggo Mortensen');
INSERT INTO actors(id, actor_name) VALUES (11, 'Benedict Cumberbatch');
INSERT INTO actors(id, actor_name) VALUES (12, 'Keira Knightley');
INSERT INTO actors(id, actor_name) VALUES (13, 'Russell Crowe');
INSERT INTO actors(id, actor_name) VALUES (14, 'Joaquin Phoenix');
INSERT INTO actors(id, actor_name) VALUES (15, 'Michael Caine');
INSERT INTO actors(id, actor_name) VALUES (16, 'Matthew McConaughey');
INSERT INTO actors(id, actor_name) VALUES (17, 'Tom Hanks');
INSERT INTO actors(id, actor_name) VALUES (18, 'Halle Berry');
INSERT INTO actors(id, actor_name) VALUES (19, 'Chiwetel Ejiofor');
INSERT INTO actors(id, actor_name) VALUES (20, 'Brad Pitt');
INSERT INTO actors(id, actor_name) VALUES (21, 'Jared Leto');
INSERT INTO actors(id, actor_name) VALUES (22, 'Cate Blanchett');
INSERT INTO actors(id, actor_name) VALUES (23, 'Jamie Foxx');
INSERT INTO actors(id, actor_name) VALUES (24, 'Leonardo DiCaprio');
INSERT INTO actors(id, actor_name) VALUES (25, 'Edward Norton');
INSERT INTO actors(id, actor_name) VALUES (26, 'Helena Bonham Carter');
INSERT INTO actors(id, actor_name) VALUES (27, 'Christian Bale');
INSERT INTO actors(id, actor_name) VALUES (28, 'Heath Ledger');
INSERT INTO actors(id, actor_name) VALUES (29, 'Marlon Brando');
INSERT INTO actors(id, actor_name) VALUES (30, 'Al Pacino');
INSERT INTO actors(id, actor_name) VALUES (31, 'Charlie Chaplin');
INSERT INTO actors(id, actor_name) VALUES (32, 'Judy Garland');

--movies
INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (1, 'Star Wars: Episode IV - A New Hope', 'jpg', '1977', 'https://www.youtube.com/watch?v=1g3_CFmnU7k', 'George Lucas', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (1, 1);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (1, 2);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (1, 3);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre, favorited) VALUES (2, 'Into the Wild', 'jpg', '2007', 'https://www.youtube.com/watch?v=g7ArZ7VD-QQ', 'Sean Penn', 'Drama', 1);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (2, 4);


INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre, favorited) VALUES (3, 'Dune', 'jpg', '1984', 'https://www.youtube.com/watch?v=hzUlXEyvJeA', 'David Lynch', 'Science Fiction', 1);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (3, 5);


INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (4, '2001: A Space Odyssey', 'jpg', '1968', 'https://www.youtube.com/watch?v=oR_e9y-bka0', 'Stanley Kubrick', 'Science Fiction');

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (5, 'Mad Max: Fury Road', 'jpg', '2015', 'https://www.youtube.com/watch?v=hEJnMQG9ev8', 'George Miller', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (5, 6);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (5, 7);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (6, 'Blade Runner 2049', 'jpg', '2017', 'https://www.youtube.com/watch?v=gCcx85zbxz4', 'Denis Villeneuve', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (6, 8);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (6, 2);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre, favorited) VALUES (7, 'The Lord of the Rings: The Fellowship of the Ring', 'jpg', '2001', 'https://www.youtube.com/watch?v=V75dMMIW2B4', 'Peter Jackson', 'Fantasy', 1);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (7, 9);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (7, 10);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (8, 'The Imitation Game', 'jpg', '2014', 'https://www.youtube.com/watch?v=nuPZUUED5uk', 'Morten Tyldum', 'Biography');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (8, 11);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (8, 12);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (9, 'Gladiator', 'jpg','2000', 'https://www.youtube.com/watch?v=uvbavW31adA', 'Ridley Scott', 'Action');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (9, 13);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (9, 14);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (10, 'Interstellar', 'jpg', '2014', 'https://www.youtube.com/watch?v=zSWdZVtXT7E', 'Christopher Nolan', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (10, 15);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (10, 16);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre, favorited) VALUES (11, 'Cloud Atlas', 'jpg', '2012', 'https://www.youtube.com/watch?v=hWnAqFyaQ5s', 'Tom Tykwer', 'Science Fiction', 1);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (11, 17);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (11, 18);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (12, '12 Years a Slave', 'jpg', '2013', 'https://www.youtube.com/watch?v=z02Ie8wKKRg', 'Steve McQueen', 'Biography');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (12, 19);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (12, 20);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (13, 'Mr. Nobody', 'jpg', '2009', 'https://www.youtube.com/watch?v=vXf3gVYXlHg', 'Jaco Van Dormael', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (13, 21);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (14, 'The Curious Case of Benjamin Button', 'jpg', '2008', 'https://www.youtube.com/watch?v=iH6FdW39Hag', 'David Fincher', 'Drama');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (14, 20);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (14, 22);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (15, 'Django Unchained', 'jpg', '2012', 'https://www.youtube.com/watch?v=ztD3mRMdqSw', 'Quentin Tarantino', 'Action');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (15, 23);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (16, 'Inception', 'jpg','2010', 'https://www.youtube.com/watch?v=YoHD9XEInc0', 'Christopher Nolan', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (16, 24);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (17, 'Fight Club', 'jpg','1999', 'https://www.youtube.com/watch?v=qtRKdVHc-cE', 'David Fincher', 'Drama');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (17, 20);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (17, 25);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (17, 26);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (18, 'The Dark Knight', 'jpg', '2008', 'https://www.youtube.com/watch?v=EXeTwQWrcwY', 'Christopher Nolan', 'Action');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (18, 15);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (18, 27);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (18, 28);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (19, 'Blade Runner', 'jpg', '1982', 'https://www.youtube.com/watch?v=eogpIG53Cis', 'Ridley Scott', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (19, 2);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (20, 'Dunkirk', 'jpg','2017', 'https://www.youtube.com/watch?v=F-eMt3SrfFU', 'Christoper Nolan', 'Action');

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (21, 'The Godfather', 'jpg', '1972', 'https://www.youtube.com/watch?v=sY1S34973zA', 'Francis Ford Coppola', 'Drama');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (21, 29);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (21, 30);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (22, 'The Great Dictator', 'jpg', '1940', 'https://www.youtube.com/watch?v=zroWIN-lS8E', 'Charlie Chaplin', 'Drama');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (22, 31);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (23, 'Star Wars: Episode V - The Empire Strikes Back', 'jpg', '1980', 'https://www.youtube.com/watch?v=JNwNXF9Y6kY', 'Irvin Kershner', 'Science Fiction');
INSERT INTO movie_actors(movies_id, actors_id) VALUES (23, 1);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (23, 2);
INSERT INTO movie_actors(movies_id, actors_id) VALUES (23, 3);

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, genre) VALUES (24, 'Thor: Ragnorak', 'jpg', '2017', 'https://www.youtube.com/watch?v=ue80QwXMRHg', 'Action');

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (25, 'Prisoners', 'jpg', '2013', 'https://www.youtube.com/watch?v=bpXfcTF6iVk', 'Denis Villeneuve', 'Drama');

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (26, 'The Martian', 'jpg', '2015', 'https://www.youtube.com/watch?v=ej3ioOneTy8', 'Ridley Scott', 'Science Fiction');

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, genre) VALUES (27, 'Avengers: Endgame', 'jpg', '2015', 'https://www.youtube.com/watch?v=TcMBFSGVi1c', 'Action');

INSERT INTO movies (id, movie_title, file_ext, release_date, trailer_link, genre) VALUES (28, 'The Secret Life of Walter Mitty', 'jpg', '2013', 'https://www.youtube.com/watch?v=QD6cy4PBQPI', 'Drama');


-- End of sql transaction
COMMIT;
