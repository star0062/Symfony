PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, score INTEGER NOT NULL);
INSERT INTO user VALUES(1,'glouglou','[]','$2y$13$M976Pt3sOXMlab2p5seXZetqDrX.g.plsFYxsuT2wBJUVWj2T6NVK','glouglou@gmail.com',0,0);
INSERT INTO user VALUES(2,'page','[]','$2y$13$DWSO8IveuP86BmYcoQv0d.osB0ktnvgwGgmiXmCK3pjEkiHM1AezW','page@gmail.com',0,0);
COMMIT;
