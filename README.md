# hotelreview

Database name: hotelreview
3 tables: users, hotels, reviews

users (user_id, user_name, email, pass, registration_date) user_id is Primary Key
hotels (hotel_id, hotel_name, address, city, country, creation_date) hotel_id is Primary Key
reviews (review_id, user_id, hotel_id, rating, creation_date) review_id is Primary Key, user_id and hotel_id are Foreign Keys from users and hotels, rating ranges from 1 to 5
