DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;

CREATE TABLE users(
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(100) NOT NULL,
    superuser int(1) default 0 check (superuser in (0,1))
);

INSERT INTO users(username, password, superuser) VALUES ('coynem6', md5('password'), 1);
INSERT INTO users(username, password, superuser) VALUES ('cerminarol1', md5('password'), 1);
INSERT INTO users(username, password, superuser) VALUES ('notsuper', md5('password'), 0);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner VARCHAR(50) NOT NULL,
    caption TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner) REFERENCES users(username) ON DELETE CASCADE
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    commenter VARCHAR(50) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (commenter) REFERENCES users(username) ON DELETE CASCADE
);
