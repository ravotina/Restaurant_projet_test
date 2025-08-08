

CREATE TABLE restaurants (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    lat DECIMAL(9, 6) NOT NULL,
    lng DECIMAL(9, 6) NOT NULL,
    image_name VARCHAR(255) NOT NULL
);


CREATE TABLE menus (
    id SERIAL PRIMARY KEY,
    restaurant_id INT REFERENCES restaurants(id),
    menu TEXT NOT NULL
);

CREATE EXTENSION postgis;
