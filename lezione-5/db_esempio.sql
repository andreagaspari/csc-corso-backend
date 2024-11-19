
-- Database: `csc_database`

-- --------------------------------------------------------

-- Struttura della tabella `utenti`
CREATE TABLE `utenti` (
  `ID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mail` varchar(64) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nicename` varchar(64) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `surname` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserimento dati nella tabella `utenti`
INSERT INTO `utenti` (`ID`, `username`, `password`, `mail`, `last_login`, `nicename`, `name`, `surname`) VALUES
(1, 'john_doe', 'password123', 'john.doe@example.com', '2024-11-19 10:00:00', 'John Doe', 'John', 'Doe'),
(2, 'jane_smith', 'password123', 'jane.smith@example.com', '2024-11-19 10:05:00', 'Jane Smith', 'Jane', 'Smith'),
(3, 'alice_jones', 'password123', 'alice.jones@example.com', '2024-11-19 10:10:00', 'Alice Jones', 'Alice', 'Jones'),
(4, 'bob_brown', 'password123', 'bob.brown@example.com', '2024-11-19 10:15:00', 'Bob Brown', 'Bob', 'Brown'),
(5, 'emma_watson', 'password123', 'emma.watson@example.com', '2024-11-19 10:20:00', 'Emma Watson', 'Emma', 'Watson');

-- --------------------------------------------------------

-- Struttura della tabella `articoli`
CREATE TABLE `articoli` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` mediumtext DEFAULT NULL,
  `date` date NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `url` varchar(128) NOT NULL,
  `author_id` smallint(6) UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`author_id`) REFERENCES `utenti`(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserimento dati nella tabella `articoli`
INSERT INTO `articoli` (`ID`, `title`, `content`, `date`, `subtitle`, `url`, `author_id`) VALUES
(1, 'Introduction to Python', 'A comprehensive guide to Python programming language, covering basics to advanced topics.', '2024-11-01', 'Learn Python Basics', 'introduction-to-python', 1),
(2, 'Understanding Databases', 'Learn the fundamental concepts of relational and non-relational databases.', '2024-11-02', 'Database Basics', 'understanding-databases', 2),
(3, 'What is Cloud Computing?', 'Explore the benefits and architecture of cloud computing in modern IT infrastructure.', '2024-11-03', 'Cloud Computing 101', 'what-is-cloud-computing', 3),
(4, 'Mastering Git', 'A step-by-step guide to using Git for version control and collaborative development.', '2024-11-04', 'Version Control with Git', 'mastering-git', 4),
(5, 'Web Development Trends 2024', 'Discover the latest trends in web development, including frameworks and tools.', '2024-11-05', 'Future of Web Development', 'web-development-trends-2024', 5),
(6, 'Getting Started with Docker', 'Learn how to containerize your applications using Docker.', '2024-11-06', 'Docker Basics', 'getting-started-with-docker', 1),
(7, 'Introduction to Machine Learning', 'Understand the basics of machine learning and its applications.', '2024-11-07', 'Machine Learning 101', 'introduction-to-machine-learning', 2),
(8, 'Cybersecurity Essentials', 'Learn how to protect your systems and data in the digital age.', '2024-11-08', 'Stay Safe Online', 'cybersecurity-essentials', 3),
(9, 'Building RESTful APIs', 'A guide to designing and building RESTful APIs for your applications.', '2024-11-09', 'API Development', 'building-restful-apis', 4),
(10, 'Introduction to PHP', 'Learn the basics of PHP for web development.', '2024-11-10', 'PHP Basics', 'introduction-to-php', 5),
(11, 'HTML & CSS for Beginners', 'An introduction to building static websites using HTML and CSS.', '2024-11-11', 'Web Design Basics', 'html-css-for-beginners', 1),
(12, 'Advanced JavaScript Concepts', 'Deep dive into closures, promises, and async/await in JavaScript.', '2024-11-12', 'JavaScript Advanced', 'advanced-javascript-concepts', 2),
(13, 'Getting Started with React', 'Learn how to build dynamic web applications with React.', '2024-11-13', 'React Basics', 'getting-started-with-react', 3),
(14, 'Understanding DevOps', 'An introduction to DevOps practices and tools for faster deployments.', '2024-11-14', 'DevOps Overview', 'understanding-devops', 4),
(15, 'SEO Best Practices', 'Optimize your website for search engines with these best practices.', '2024-11-15', 'Boost Your SEO', 'seo-best-practices', 5),
(16, 'Intro to Kubernetes', 'Learn how to orchestrate containers using Kubernetes.', '2024-11-16', 'Kubernetes Basics', 'intro-to-kubernetes', 1),
(17, 'Data Analysis with Python', 'Explore how to analyze data using Python libraries like pandas and matplotlib.', '2024-11-17', 'Analyze Data', 'data-analysis-with-python', 2),
(18, 'Introduction to APIs', 'Learn the basics of using and building APIs.', '2024-11-18', 'APIs Explained', 'introduction-to-apis', 3),
(19, 'Ethical Hacking Basics', 'Understand the foundations of ethical hacking and penetration testing.', '2024-11-19', 'Hack Responsibly', 'ethical-hacking-basics', 4),
(20, 'Java Fundamentals', 'Learn the core concepts of Java programming.', '2024-11-20', 'Java Programming', 'java-fundamentals', 5);
