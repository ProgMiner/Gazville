# TODO

+ Add Register controller
+ Make admin-panel

# DB Structure

+ **keys**
  + *int* **id** - Identificator
  + *varchar(32)* **hash** - Key hash
  + *text* **key** - Encrypted key
  + *enum* **type** - Key type: *user*/*session*/*group*/*invitation*
  + *int* **owner** - Owner's user-id

+ **log**
  + *int* **id** - Identificator
  + *datetime* **time** - Timestamp
  + *text* **msg** - Message
  + *int* **ip** - Request IP
  + *text* **file** - File and line
  + *text* **url** - URL

+ **meta**
  + *int* **id** - Identificator
  + *varchar(255)* **field** - Field name
  + *text* **value** - Field value
  + *varchar(32)* **key** - Key hash
  + *varchar(32)* **hash** - Field hash
  + *enum* **type** - Field type: *user*/*group*/*post*
  + *int* **owner** - Owner's id

+ **pages**
  + *int* **id** - Identificator
  + *varchar(255)* **url** - Alternative URL for page
  + *text* **title** - Page title
  + *text* **content** - Page content
  + *int* **owner** - Owner's user-id

+ **tasks**
  + *int* **id** - Identificator
  + *text* **task** - Task
  + *varchar(32)* **key** - Required key hash
  + *enum* **status** - Task status: *waiting*/*processing*
  + *int* **owner** - Task owner's user-id

+ **users**
  + *int* **id** - Identificator
  + *varchar(255)* **login** - User's login
  + *int* **vk_id** - VK.com user-id
