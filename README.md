# hotelreview
How to run:

Run home.php via xampp through: localhost/--your folder name--/home.php



Database name: hotelreview
3 tables: users, hotels, reviews

users (user_id, user_name, email, pass, registration_date) user_id is Primary Key

hotels (hotel_id, hotel_name, address, city, country, creation_date, image) hotel_id is Primary Key, image is where file location for images are stored

reviews (review_id, user_id, hotel_id, rating, creation_date) review_id is Primary Key, user_id and hotel_id are Foreign Keys from users and hotels, rating ranges from 1 to 5

Table sqls:

CREATE TABLE users(
user_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
user_name VARCHAR(40) NOT NULL,
email VARCHAR(60) UNIQUE NOT NULL,
pass CHAR(40) NOT NULL,
registration_date DATETIME NOT NULL,
PRIMARY KEY(user_id)
);

CREATE TABLE hotels(
hotel_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
hotel_name VARCHAR(100) NOT NULL,
address VARCHAR(255) NOT NULL,
city VARCHAR(100) NOT NULL,
country VARCHAR(100) NOT NULL,
image VARCHAR(255) NOT NULL,
creation_date DATETIME NOT NULL,
PRIMARY KEY(hotel_id)
);

CREATE TABLE reviews(
    review_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id MEDIUMINT UNSIGNED NOT NULL,
    hotel_id MEDIUMINT UNSIGNED NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    description TEXT NOT NULL,
    creation_date DATETIME NOT NULL,
    PRIMARY KEY(review_id),
    CONSTRAINT FK_userOrder FOREIGN KEY (user_id)
        REFERENCES users(user_id),
    CONSTRAINT FK_hotelOrder FOREIGN KEY (hotel_id)
        REFERENCES hotels(hotel_id),
    CONSTRAINT unique_user_hotel_review UNIQUE (user_id, hotel_id)

);

