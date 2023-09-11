<?php
// for home page search
define(
    "SELECT_SEARCH_ALL",
    "SELECT title FROM `home_search` WHERE title LIKE ?  UNION
    SELECT author_name FROM `home_search` WHERE author_name LIKE ?  UNION
    SELECT pub_name FROM `home_search` WHERE pub_name LIKE ?  UNION
    SELECT cat_name FROM `home_search` WHERE cat_name LIKE ? LIMIT 20"
);
// for details or home page search result
define(
    "SELECT_SEARCH_LOAD_ALL",
    "SELECT * FROM `home_search` WHERE 
    title LIKE ? OR author_name LIKE ? OR cat_name LIKE ?
    GROUP BY title ORDER BY title ASC LIMIT ?,?"
);

// for main book, author , publisher, book pages
define(
    "SELECT_BOOK",
    "SELECT book.book_id, book.title, book.photo, book.rating , author.author_id, author.author_name FROM book_author_rel
    NATURAL JOIN book NATURAL JOIN author GROUP BY book.title
    ORDER BY book.title ASC LIMIT  ?,?"
);
define(
    "SELECT_AUTHOR",
    "SELECT author.author_id, author.author_name , author.author_img, COUNT(book_author_rel.author_id) AS book_no FROM author
    LEFT JOIN book_author_rel ON author.author_id = book_author_rel.author_id
      GROUP BY author.author_id
      ORDER BY author.author_id ASC LIMIT  ?,?"
);
define(
    "SELECT_PUBLISHER",
    "SELECT publisher.pub_id, publisher.pub_name , COUNT(book_publisher_rel.pub_id) AS book_no FROM publisher
    LEFT JOIN book_publisher_rel ON publisher.pub_id = book_publisher_rel.pub_id
      GROUP BY publisher.pub_id
      ORDER BY publisher.pub_id ASC LIMIT  ?,?"
);
define(
    "SELECT_CATEGORY",
    "SELECT categories.cat_id, categories.cat_name, COUNT(book.cat_id) AS book_no FROM categories
    LEFT JOIN book ON categories.cat_id = book.cat_id
      GROUP BY categories.cat_id
      ORDER BY categories.cat_id ASC LIMIT ?,?"
);

// for check if book is  borrow onr not
define(
    "SELECT_BOOK_STATE",
    "SELECT COUNT(book.book_id) as book_state FROM book
    INNER JOIN part ON book.book_id = part.book_id
    INNER JOIN part_copy_rel ON part.part_id = part_copy_rel.part_id
    INNER JOIN borrow ON part_copy_rel.copy_no = borrow.copy_no
    WHERE book.book_id = ?"
);

// for search in publisher and author and category
define(
    "SEARCH_CATEGORY",
    "SELECT categories.cat_id, categories.cat_name, COUNT(book.cat_id) AS book_no FROM categories
    LEFT JOIN book ON categories.cat_id = book.cat_id
    WHERE categories.cat_name  LIKE ? GROUP BY categories.cat_id ORDER BY categories.cat_id ASC LIMIT ?,?"
);
define(
    "SEARCH_PUBLISHER",
    "SELECT publisher.pub_id, publisher.pub_name , COUNT(book_publisher_rel.pub_id) AS book_no FROM publisher
    LEFT JOIN book_publisher_rel ON publisher.pub_id = book_publisher_rel.pub_id
    WHERE publisher.pub_name  LIKE ? GROUP BY publisher.pub_id ORDER BY publisher.pub_id ASC LIMIT ?,?"
);
define(
    "SEARCH_AUTHOR",
    "SELECT author.author_id, author.author_name , author.author_img, COUNT(book_author_rel.author_id) AS book_no FROM author
    LEFT JOIN book_author_rel ON author.author_id = book_author_rel.author_id
    WHERE author.author_name  LIKE ? GROUP BY author.author_id ORDER BY author.author_id ASC LIMIT ?,?"
);

// for author and publisher and category section book 
define(
    "SELECT_AUTHOR_BOOK",
    "SELECT book.book_id, book.title, book.photo, book.rating , 
    author.author_id, author.author_name, author.author_description, author.author_img,
    categories.cat_id, categories.cat_name
    FROM book_author_rel
        NATURAL JOIN book
        NATURAL JOIN author
        NATURAL JOIN categories
        WHERE author_id = ? GROUP BY book.title ORDER BY book.title ASC LIMIT ?,?"
);
define(
    "SELECT_CATEGORY_BOOK",
    "SELECT book.book_id, book.title, book.photo, book.rating , 
        author.author_id, author.author_name,
        categories.cat_id, categories.cat_name
        FROM book_author_rel
            NATURAL JOIN book
            NATURAL JOIN author
            NATURAL JOIN categories
            WHERE cat_id = ? GROUP BY book.title ORDER BY book.title ASC LIMIT ?,?"
);
define(
    "SELECT_PUBLISHER_BOOK",
    "SELECT book.book_id, book.title, book.photo, book.rating , 
    author.author_id, author.author_name,
    book_publisher_rel.pub_id
    FROM book_publisher_rel
    INNER JOIN book ON book_publisher_rel.book_id = book.book_id
    INNER JOIN book_author_rel ON book.book_id = book_author_rel.book_id
    INNER JOIN author ON book_author_rel.author_id = author.author_id
    WHERE pub_id = ? GROUP BY book.title ORDER BY book.title ASC LIMIT ?,?"
);

// for book, author , publisher, book sections pages detail and breadcrumb
define(
    "SELECT_AUTHOR_INFO",
    "SELECT `author_id`, `author_img`, `author_name`, `author_description`, `author_profession`, `author_nationality`, `author_birthday`, `author_deathday` FROM `author` WHERE `author_id` = ?"
);
define(
    "SELECT_PUBLISHER_INFO",
    "SELECT `pub_name`, `establishment_date`, `owner`, `sequential_deposit_no` FROM `publisher` WHERE `pub_id` = ?"
);
define(
    "SELECT_CATEGORY_INFO",
    "SELECT `cat_name` FROM `categories` WHERE `cat_id` = ?"
);

// for book, author , publisher, book delete pages
define(
    "DELETE_AUTHOR",
    "DELETE FROM `author` WHERE `author_id` = ?"
);
define(
    "DELETE_PUBLISHER",
    "DELETE FROM `publisher` WHERE `pub_id` = ?"
);
define(
    "DELETE_CATEGORY",
    "DELETE FROM `categories` WHERE `cat_id` = ?"
);
define(
    "DELETE_BOOK",
    "DELETE FROM `book` WHERE `book_id` = ?"
);
// for book rating
define(
    "BOOK_RATING",
    "UPDATE `book` SET `rating`= ? WHERE `book_id` = ?"
);

//Mohammed Pages
//login page
define(
    "USER_CHECK_LOGIN",
    "SELECT user_id, user_email ,user_password from user where user_email = ? 
     And user_groupid = 1 Limit 1"
);
// signup page
define(
    "USER_CHECK",
    "SELECT user_email from user where user_email = ? Limit 1"
);

//signup page
define(
    "INSERT_USER",
    "INSERT into user(user_name , user_email ,user_password , user_groupid) VALUES (? , ? ,? , 1 )"
);
//upload page 
define(
    "EDIT_FILE_PATH",
    "UPDATE part SET part_path = ? WHERE part.book_id = ?"
);
// to get pdf path
define(
    "SELECT_FILE_PATH",
    "SELECT part_path FROM part WHERE book_id = ? Limit 1"
);
// to get all book inf
define(
    "SELECT_BOOK_INFO",
    "SELECT book.* , part.* ,categories.cat_name ,author.author_id ,author.author_name ,book_publisher_rel.pub_id 
    ,publisher.pub_name as pub_name  ,language.lang_name  
    FROM book_author_rel
    INNER JOIN book ON book_author_rel.book_id = book.book_id
    INNER JOIN part ON part.book_id = book.book_id
    INNER JOIN categories ON book.cat_id = categories.cat_id
    INNER JOIN author ON book_author_rel.author_id = author.author_id
    INNER JOIN book_publisher_rel ON book_publisher_rel.book_id =book_author_rel.book_id
    INNER JOIN publisher ON book_publisher_rel.pub_id =publisher.pub_id
    INNER JOIN lang_book_rel ON lang_book_rel.book_id = book.book_id
    INNER JOIN language ON lang_book_rel.lang_id =language.lang_id 
    WHERE book_author_rel.book_id = ?    "
);
// to add category
define(
    "ADD_CATEGORY",
    "INSERT INTO categories(cat_name) VALUES(?)"
);
// to update category
define(
    "EDIT_CATEGORY",
    "UPDATE `categories` SET `cat_name`= ? WHERE `cat_id` = ?"
);
// to check category
define(
    "CHECK_CATEGORY",
    "SELECT `cat_name` FROM `categories` WHERE `cat_name`= ? LIMIT 1;"
);
// to check author
define(
    "CHECK_AUTHOR",
    "SELECT `author_name` FROM `author` WHERE `author_name` = ? LIMIT 1;"
);
// to insert author
define(
    "INSERT_AUTHOR",
    "INSERT INTO `author`(`author_img`, `author_name`, `author_description`, `author_profession`, `author_nationality`, `author_birthday`, `author_deathday`) VALUES (?, ?, ?, ?, ?, ?, ?)"
);
// to insert author
define(
    "INSERT_AUTHOR_NULL",
    "INSERT INTO `author`(`author_img`, `author_name`, `author_description`, `author_profession`, `author_nationality`, `author_birthday`) VALUES (?, ?, ?, ?, ?, ?)"
);
// to update author
define(
    "UPDATE_AUTHOR",
    "UPDATE `author` SET `author_img`= ?, `author_name`= ?, `author_description`= ?, `author_profession`= ?, `author_nationality`= ?, `author_birthday`= ?, `author_deathday`= ? WHERE `author_id` = ?"
);
// to update author
define(
    "UPDATE_AUTHOR_NULL",
    "UPDATE `author` SET `author_img`= ?, `author_name`= ?, `author_description`= ?, `author_profession`= ?, `author_nationality`= ?, `author_birthday`= ? WHERE `author_id` = ?"
);

//loaning page 
define(
    "SELECT_PART",
    "select copy_no from part_copy_rel  where part_id = ?"
);
define(
    "SELECT_BORROW",
    "select borrow_id from 	borrow  where copy_no  = ?"
);
define(
    "INSERT_LOAN",
    "INSERT into borrow(borrow_date , return_date ,user_id , copy_no) VALUES (? , ? ,? , ? )"
);
//
define(
    "SELECT_LOAN_USER",
    "SELECT user_id from borrow where user_id = ?"
);
define(
    "SELECT_LOAN_PART",
    "SELECT copy_no from borrow where copy_no = ?"
);

//add-publisher page 
define(
    "SELECT_LANG",
    "SELECT * from language"
);
define(
    "SELECT_PUB_NAME",
    "SELECT pub_name from publisher where pub_name like ? "
);
define(
    "SELECT_PUB_SEQ",
    "SELECT sequential_deposit_no from publisher where sequential_deposit_no like ? "
);
define(
    "INSERT_PUB",
    "INSERT INTO publisher(pub_name, establishment_date, owner,sequential_deposit_no)
    VALUES(?,?,?,?);"
);
define(
    "INSERT_PUB_LANG",
    "INSERT INTO lang_publisher_rel(lang_id,pub_id)
    VALUES(?,LAST_INSERT_ID());"
);

//edit pub
define(
    "SELECT_PUBLISHER_UPDATE",
    "SELECT publisher.* ,lang_publisher_rel.lang_id,language.*
    FROM publisher
    INNER JOIN lang_publisher_rel ON lang_publisher_rel.pub_id = publisher.pub_id
    INNER JOIN language ON language.lang_id = lang_publisher_rel.lang_id
    WHERE publisher.pub_id = ? "
);
//edit book
define(
    "SELECT_BOOK_UPDATE",
    // "SELECT * FROM book WHERE book_id = ?"
    "SELECT book.* ,language.lang_name,language.lang_id,categories.cat_name,book_author_rel.work_on_book ,book_author_rel.work_id ,author.author_name ,publisher.pub_id,publisher.pub_name
    FROM book
    INNER JOIN lang_book_rel ON lang_book_rel.book_id = book.book_id
    INNER JOIN language ON language.lang_id = lang_book_rel.lang_id
    
    INNER JOIN book_author_rel ON book_author_rel.book_id = book.book_id
    INNER JOIN author ON author.author_id = book_author_rel.author_id
    
    INNER JOIN book_publisher_rel ON book_publisher_rel.book_id = book.book_id
    INNER JOIN publisher ON publisher.pub_id = book_publisher_rel.pub_id
    
    INNER JOIN categories ON categories.cat_id = book.cat_id
    WHERE book.book_id = ?
    "
);

define(
    "EDIT_PUB",
    "UPDATE publisher SET publisher.pub_name= ? ,publisher.establishment_date =? ,publisher.owner =? ,publisher.sequential_deposit_no	=? WHERE publisher.pub_id = ?"
);
define(
    "EDIT_PUB_LANG",
    "UPDATE lang_publisher_rel SET lang_publisher_rel.lang_id= ? WHERE lang_publisher_rel.pub_id = ?"
);
define(
    "INSERT_BOOK_INFO",
    "INSERT INTO `book` (`book_id`, `title`, `subtitle`, `photo`, `description`, `depository_no`, `isbn`, `dewey_no`, `rating`, `publication_place`, `cat_id`)
    VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
);
define(
    "INSERT_BOOK_AUTHOR_REL",
    "INSERT INTO `book_author_rel` (`rel_id`, `work_on_book`, `work_id`, `book_id`, `author_id`) 
    VALUES (NULL, ?, ?, ?, ?);"
);
define(
    "INSERT_BOOK_LANG_REL",
    "INSERT INTO `lang_book_rel` (`rel_id`, `lang_id`, `book_id`) 
    VALUES (NULL, ?, ?);"
);
define(
    "INSERT_BOOK_PUB_REL",
    "INSERT INTO `book_publisher_rel` (`rel_id`, `book_id`, `pub_id`) 
    VALUES (NULL, ?, ?);"
);
define(
    "INSERT_BOOK_PART",
    "INSERT INTO `part` (`part_id`, `part_no`, `part_path`, `book_id`, `publication_date`, `price`, `pages_no`, `size`, `edition_no`, `edition_desc`, `format`, `num_of_copies`) 
    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
);
define(
    "INSERT_PART_COPY_REL",
    "INSERT INTO part_copy_rel (copy_no, part_id) VALUES (NULL, ?);"
);
define(
    "SELECT_SERIES_NAME",
    "SELECT * from series where series_name like ? "
);
define(
    "INSERT_SERIES",
    "INSERT INTO `series` (`series_id`, `series_name`) 
    VALUES (NULL, ?); "
);
define(
    "INSERT_SERIES_PART_REL",
    "INSERT INTO `part_series_rel` (`rel_id`, `number_in_series`, `series_id`, `part_id`) 
    VALUES (NULL, ?, ?, ?);"
);
define(
    "INSERT_SERIES_PART_REL_LAST",
    "INSERT INTO `part_series_rel` (`rel_id`, `number_in_series`, `series_id`, `part_id`) 
    VALUES (NULL, ?, LAST_INSERT_ID(), ?);"
);
define(
    "INSERT_ATTACHMENT",
    "INSERT INTO `attachments` (`att_id`, `att_name`, `type`, `path`, `book_id`) 
        VALUES (NULL, ?, ?, ?, ?);"
);
define(
    "SELECT_BOOK_NAME",
    "SELECT title from book where title like ? "
);
