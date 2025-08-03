create database facebook_users;
CREATE USER 'team_12'@'localhost' IDENTIFIED BY 'secad';
GRANT ALL ON facebook_users.* TO 'team_12'@'localhost';