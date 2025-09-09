CREATE TABLE results (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    exam_name VARCHAR(255) NOT NULL,
    score INT(11) NOT NULL,
    total_questions INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id)
);
