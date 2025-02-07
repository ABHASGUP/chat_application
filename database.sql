-- Create the database
CREATE DATABASE IF NOT EXISTS chat;
USE chat;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id int(11) NOT NULL AUTO_INCREMENT,
    unique_id int(200) NOT NULL,
    fname varchar(255) NOT NULL,
    lname varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    img varchar(400) NOT NULL,
    status varchar(255) NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    msg_id int(11) NOT NULL AUTO_INCREMENT,
    incoming_msg_id int(255) NOT NULL,
    outgoing_msg_id int(255) NOT NULL,
    msg text NOT NULL,
    read_status tinyint(1) DEFAULT 0,
    starred tinyint(1) DEFAULT 0,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (msg_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add indexes for better performance
ALTER TABLE users ADD INDEX idx_unique_id (unique_id);
ALTER TABLE users ADD INDEX idx_email (email);
ALTER TABLE messages ADD INDEX idx_chat_pair (incoming_msg_id, outgoing_msg_id);
ALTER TABLE messages ADD INDEX idx_read_status (read_status);
ALTER TABLE messages ADD INDEX idx_starred (starred);

-- Add foreign key constraints
ALTER TABLE messages
ADD CONSTRAINT fk_incoming_msg
FOREIGN KEY (incoming_msg_id) REFERENCES users(unique_id)
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE messages
ADD CONSTRAINT fk_outgoing_msg
FOREIGN KEY (outgoing_msg_id) REFERENCES users(unique_id)
ON DELETE CASCADE ON UPDATE CASCADE;
