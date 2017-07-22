# TODO

+ Add Register controller
+ Make admin-panel
+ Make Feedback

# DB Structure

+ **keys**
  + Indexes
    + *PRIMARY* **PRIMARY** - **key_id**
    + *INDEX* **key** - **key_type**, **user_id**
  + Columns
    + *int(11)* **key_id** - Key identificator
    + *text* **key** - Encrypted key
    + *varchar(32)* **key_hash** - Key hash
    + *enum(user, session, group, invitation)* **key_type** - Key type
    + *int(11)* **user_id** - User identificator

+ **log**
  + Indexes
    + *PRIMARY* **PRIMARY** - **msg_id**
    + *INDEX* **time** - **msg_time**
  + Columns
    + *int(11)* **msg_id** - Message identificator
    + *timestamp* **msg_time** - Message creation time
    + *text* **msg_text** - Message text
    + *int(11)* **msg_ip** - Message creation IP
    + *text* **msg_file** - Message creation file and line
    + *text* **msg_url** - Message creation URL

+ **meta**
  + Indexes
    + *PRIMARY* **PRIMARY** - **meta_id**
    + *UNIQUE* **group** - **key_hash**, **owner_type**, **owner_id**
    + *INDEX* **key** - **owner_type**, **owner_id**
  + Columns
    + *int(11)* **meta_id** - Meta identificator
    + *text* **meta_value** - Meta value
    + *varchar(32)* **meta_hash** - Meta hash
    + *varchar(32)* **key_hash** - Key hash
    + *enum(user, group, post)* **owner_type** - Owner type
    + *int(11)* **owner_id** - Owner identificator

+ **pages**
  + Indexes
    + *PRIMARY* **PRIMARY** - **page_id**
    + *UNIQUE* **url** - **page_url**
  + Columns
    + *int(11)* **page_id** - Page identificator
    + *varchar(255)* **page_url** - Alternative URL for page
    + *text* **page_title** - Page title
    + *text* **page_content** - Page content
    + *int(11)* **user_id** - User identificator

+ **tasks**
  + Indexes
    + *PRIMARY* **PRIMARY** - **task_id**
  + Columns
    + *int(11)* **task_id** - Task identificator
    + *text* **task** - Task
    + *varchar(32)* **key_hash** - Required key hash
    + *enum(waiting, processing)* **task_status** - Task status
    + *int(11)* **user_id** - User identificator

+ **users**
  + Indexes
    + *PRIMARY* **PRIMARY** - **user_id**
    + *UNIQUE* **login** - **user_login**
    + *UNIQUE* **vk_id** - **vk_id**
  + Columns
    + *int(11)* **user_id** - User identificator
    + *varchar(255)* **user_login** - User login
    + *int(11)* **vk_id** - VK identificator
