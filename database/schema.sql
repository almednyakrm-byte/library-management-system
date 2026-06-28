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
  publication_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE library_staff (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE decisions (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_books (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (book_id),
  CONSTRAINT fk_user_books_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_books_book FOREIGN KEY (book_id) REFERENCES books (id)
);

CREATE TABLE user_library_staff (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  library_staff_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (library_staff_id),
  CONSTRAINT fk_user_library_staff_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_library_staff_library_staff FOREIGN KEY (library_staff_id) REFERENCES library_staff (id)
);

CREATE TABLE user_decisions (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  decision_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (decision_id),
  CONSTRAINT fk_user_decisions_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_decisions_decision FOREIGN KEY (decision_id) REFERENCES decisions (id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO books (title, author, publication_date) VALUES
  ('Book 1', 'Author 1', '2020-01-01'),
  ('Book 2', 'Author 2', '2021-01-01'),
  ('Book 3', 'Author 3', '2022-01-01');

INSERT INTO library_staff (name, email, phone) VALUES
  ('Staff 1', 'staff1@example.com', '1234567890'),
  ('Staff 2', 'staff2@example.com', '0987654321'),
  ('Staff 3', 'staff3@example.com', '5555555555');

INSERT INTO decisions (title, description) VALUES
  ('Decision 1', 'Description 1'),
  ('Decision 2', 'Description 2'),
  ('Decision 3', 'Description 3');