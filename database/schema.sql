CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE books (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  category_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (category_id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE people (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE book_categories (
  id INT AUTO_INCREMENT,
  book_id INT NOT NULL,
  category_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (book_id),
  KEY (category_id),
  FOREIGN KEY (book_id) REFERENCES books(id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE book_people (
  id INT AUTO_INCREMENT,
  book_id INT NOT NULL,
  person_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (book_id),
  KEY (person_id),
  FOREIGN KEY (book_id) REFERENCES books(id),
  FOREIGN KEY (person_id) REFERENCES people(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO categories (name)
VALUES ('فئة 1'), ('فئة 2'), ('فئة 3');

INSERT INTO books (title, author, category_id)
VALUES ('كتاب 1', 'كاتب 1', 1), ('كتاب 2', 'كاتب 2', 2), ('كتاب 3', 'كاتب 3', 3);

INSERT INTO people (name, email)
VALUES ('شخص 1', 'شخص1@example.com'), ('شخص 2', 'شخص2@example.com'), ('شخص 3', 'شخص3@example.com');

INSERT INTO book_categories (book_id, category_id)
VALUES (1, 1), (2, 2), (3, 3);

INSERT INTO book_people (book_id, person_id)
VALUES (1, 1), (2, 2), (3, 3);