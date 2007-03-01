CREATE TABLE VCD_SESSIONS
( SESSION_ID VARCHAR2(50) NOT NULL,
SESSION_USER_ID NUMBER(10,0) NOT NULL,
SESSION_START NUMBER(10,0) NOT NULL,
SESSION_IP VARCHAR2(15) NOT NULL
)
/
CREATE TABLE VCD_SETTINGS
( SETTINGS_ID NUMBER(10,0) NOT NULL,
SETTINGS_KEY VARCHAR2(50) NOT NULL,
SETTINGS_VALUE VARCHAR2(4000),
SETTINGS_DESCRIPTION VARCHAR2(4000),
ISPROTECTED NUMBER(3,0),
SETTINGS_TYPE VARCHAR2(20),
constraint vcd_settings_pk primary key (SETTINGS_ID)
)
/
CREATE TABLE VCD_COVERSALLOWEDONMEDIATYPES
( COVER_TYPE_ID NUMBER(10,0) NOT NULL,
MEDIA_TYPE_ID NUMBER(10,0) NOT NULL
)
/
CREATE TABLE VCD_COVERTYPES
( COVER_TYPE_ID NUMBER(10,0) NOT NULL,
COVER_TYPE_NAME VARCHAR2(50) NOT NULL,
COVER_TYPE_DESCRIPTION VARCHAR2(100),
constraint vcd_covertypes_pk PRIMARY KEY (COVER_TYPE_ID)
)
/
CREATE TABLE VCD_MEDIATYPES
( MEDIA_TYPE_ID NUMBER(10,0) NOT NULL,
MEDIA_TYPE_NAME VARCHAR2(50) NOT NULL,
PARENT_ID NUMBER(10,0),
MEDIA_TYPE_DESCRIPTION VARCHAR2(100),
constraint vcd_mediatypes_pk PRIMARY KEY (MEDIA_TYPE_ID)
)
/
CREATE TABLE VCD_USERROLES
( ROLE_ID NUMBER(10,0) NOT NULL,
ROLE_NAME VARCHAR2(50) NOT NULL,
ROLE_DESCRIPTION VARCHAR2(100),
constraint vcd_userroles_pk PRIMARY KEY (ROLE_ID)
)
/
CREATE TABLE VCD_USERPROPERTIES
( PROPERTY_ID NUMBER(10,0) NOT NULL,
PROPERTY_NAME VARCHAR2(50) NOT NULL,
PROPERTY_DESCRIPTION VARCHAR2(80),
constraint vcd_userproperties_pk PRIMARY KEY (PROPERTY_ID)
)
/
CREATE TABLE VCD_PROPERTIESTOUSER
( USER_ID NUMBER(10,0) NOT NULL,
PROPERTY_ID NUMBER(10,0) NOT NULL
)
/
CREATE TABLE VCD_SOURCESITES
( SITE_ID NUMBER(10,0) NOT NULL,
SITE_NAME VARCHAR2(50) NOT NULL,
SITE_ALIAS VARCHAR2(50),
SITE_HOMEPAGE VARCHAR2(80) NOT NULL,
SITE_GETCOMMAND VARCHAR2(100),
SITE_ISFETCHABLE NUMBER(3,0) NOT NULL,
SITE_CLASSNAME VARCHAR2(20),
SITE_IMAGE VARCHAR2(50),
constraint vcd_sourcesites_pk PRIMARY KEY (SITE_ID)
)
/
CREATE TABLE VCD_USERS
( USER_ID NUMBER(10,0) NOT NULL,
USER_NAME VARCHAR2(80) NOT NULL,
USER_PASSWORD VARCHAR2(32) NOT NULL,
USER_FULLNAME VARCHAR2(100) NOT NULL,
USER_EMAIL VARCHAR2(80) NOT NULL,
ROLE_ID NUMBER(10,0) NOT NULL,
IS_DELETED NUMBER(3,0) NOT NULL,
DATE_CREATED DATE NOT NULL,
constraint vcd_users_pk PRIMARY KEY (USER_ID)
)
/
CREATE TABLE VCD_IMAGES
( IMAGE_ID NUMBER(10,0) NOT NULL,
NAME VARCHAR2(80) NOT NULL,
IMAGE_TYPE VARCHAR2(12) NOT NULL,
IMAGE_SIZE NUMBER(10,0) NOT NULL,
IMAGE BLOB,
constraint vcd_images_pk PRIMARY KEY (IMAGE_ID)
)
/
CREATE TABLE VCD_MOVIECATEGORIES
( CATEGORY_ID NUMBER(10,0) NOT NULL,
CATEGORY_NAME VARCHAR2(100) NOT NULL,
constraint vcd_moviecategories_pk PRIMARY KEY (CATEGORY_ID)
)
/
CREATE TABLE VCD_VCDTOUSERS
( VCD_ID NUMBER(10,0) NOT NULL,
USER_ID NUMBER(10,0) NOT NULL,
MEDIA_TYPE_ID NUMBER(10,0) NOT NULL,
DISC_COUNT NUMBER(10,0) NOT NULL,
DATE_ADDED DATE NOT NULL,
constraint vcd_vcdtousers_pk PRIMARY KEY (VCD_ID,USER_ID,MEDIA_TYPE_ID)
)
/
CREATE TABLE VCD_COVERS
( COVER_ID NUMBER(10,0) NOT NULL,
VCD_ID NUMBER(10,0) NOT NULL,
COVER_TYPE_ID NUMBER(10,0) NOT NULL,
COVER_FILENAME VARCHAR2(100) NOT NULL,
COVER_FILESIZE NUMBER(10,0),
USER_ID NUMBER(10,0) NOT NULL,
DATE_ADDED DATE NOT NULL,
IMAGE_ID NUMBER(10,0),
constraint vcd_covers_pk PRIMARY KEY (COVER_ID)
)
/
CREATE TABLE VCD_PORNSTARS
( PORNSTAR_ID NUMBER(10,0) NOT NULL,
NAME VARCHAR2(4000),
HOMEPAGE VARCHAR2(100),
IMAGE_NAME VARCHAR2(100),
BIOGRAPHY VARCHAR2(4000),
constraint vcd_pornstars_pk PRIMARY KEY (PORNSTAR_ID)
)
/
CREATE TABLE VCD_VCDTOPORNSTARS
( VCD_ID NUMBER(10,0) NOT NULL,
PORNSTAR_ID NUMBER(10,0) NOT NULL
)
/
CREATE UNIQUE INDEX PORNSTARTOMOVIE ON
VCD_VCDTOPORNSTARS (VCD_ID, PORNSTAR_ID)
/
CREATE TABLE VCD_PORNCATEGORIES
( CATEGORY_ID NUMBER(10,0) NOT NULL,
CATEGORY_NAME VARCHAR2(50) NOT NULL,
constraint vcd_porncategories_pk PRIMARY KEY (CATEGORY_ID)
)
/
CREATE TABLE VCD_USERWISHLIST
( USER_ID NUMBER(10,0) NOT NULL,
VCD_ID NUMBER(10,0) NOT NULL
)
/
CREATE TABLE VCD_VCDTOPORNCATEGORIES
( VCD_ID NUMBER(10,0) NOT NULL,
CATEGORY_ID NUMBER(10,0) NOT NULL
)
/
CREATE TABLE VCD_PORNSTUDIOS
( STUDIO_ID NUMBER(10,0) NOT NULL,
STUDIO_NAME VARCHAR2(4000),
constraint vcd_pornstudios_pk PRIMARY KEY (STUDIO_ID)
)
/
CREATE TABLE VCD_COMMENTS
( COMMENT_ID NUMBER(10,0) NOT NULL,
VCD_ID NUMBER(10,0) NOT NULL,
USER_ID NUMBER(10,0) NOT NULL,
COMMENT_DATE DATE NOT NULL,
COMMENTS VARCHAR2(4000),
ISPRIVATE NUMBER(3,0) NOT NULL,
constraint vcd_comments_pk PRIMARY KEY (COMMENT_ID)
)
/
CREATE TABLE VCD_USERLOANS
( LOAN_ID NUMBER(10,0) NOT NULL,
VCD_ID NUMBER(10,0) NOT NULL,
OWNER_ID NUMBER(10,0) NOT NULL,
BORROWER_ID NUMBER(10,0) NOT NULL,
DATE_OUT DATE NOT NULL,
DATE_IN DATE,
constraint vcd_userloans_pk PRIMARY KEY (LOAN_ID)
)
/
CREATE TABLE VCD_VCDTOPORNSTUDIOS
( VCD_ID NUMBER(10,0) NOT NULL,
STUDIO_ID NUMBER(10,0) NOT NULL
)
/
CREATE TABLE VCD_SCREENSHOTS
( VCD_ID NUMBER(10,0) NOT NULL,
constraint vcd_screenshots_pk PRIMARY KEY (VCD_ID)
)
/
CREATE TABLE VCD_VCDTOSOURCES
( VCD_ID NUMBER(10,0) NOT NULL,
SITE_ID NUMBER(10,0) NOT NULL,
EXTERNAL_ID VARCHAR2(32) NOT NULL
)
/
CREATE TABLE VCD
( VCD_ID NUMBER(10,0) NOT NULL,
TITLE VARCHAR2(4000),
CATEGORY_ID NUMBER(10,0) NOT NULL,
YEAR NUMBER(10,0),
constraint vcd_pk PRIMARY KEY (VCD_ID)
)
/
CREATE TABLE VCD_IMDB
( IMDB VARCHAR2(32) NOT NULL,
TITLE VARCHAR2(4000),
ALT_TITLE1 VARCHAR2(4000),
ALT_TITLE2 VARCHAR2(4000),
IMAGE VARCHAR2(100),
YEAR NUMBER(10,0),
PLOT VARCHAR2(4000),
DIRECTOR VARCHAR2(100),
FCAST VARCHAR2(4000),
RATING VARCHAR2(5),
RUNTIME NUMBER(10,0),
COUNTRY VARCHAR2(80),
GENRE VARCHAR2(100),
constraint vcd_imdb_pk PRIMARY KEY (IMDB)
)
/
CREATE TABLE VCD_BORROWERS
( BORROWER_ID NUMBER(10,0) NOT NULL,
OWNER_ID NUMBER(10,0) NOT NULL,
NAME VARCHAR2(100) NOT NULL,
EMAIL VARCHAR2(50) NOT NULL,
constraint vcd_borrowers_pk PRIMARY KEY (BORROWER_ID)
)
/
CREATE TABLE VCD_RSSFEEDS
( FEED_ID NUMBER(10,0) NOT NULL,
USER_ID NUMBER(10,0) NOT NULL,
FEED_NAME VARCHAR2(60) NOT NULL,
FEED_URL VARCHAR2(150) NOT NULL,
ISADULT NUMBER(3,0),
ISSITE NUMBER(3,0),
constraint vcd_rssfeeds_pk PRIMARY KEY (FEED_ID)
)
/
CREATE TABLE VCD_METADATA
( METADATA_ID NUMBER(10,0) NOT NULL,
RECORD_ID NUMBER(10,0) NOT NULL,
MEDIATYPE_ID NUMBER(10,0) NOT NULL,
USER_ID NUMBER(10,0) NOT NULL,
TYPE_ID NUMBER(10,0) NOT NULL,
METADATA_VALUE VARCHAR2(250) NOT NULL,
constraint vcd_metadata_pk PRIMARY KEY (METADATA_ID)
)
/
CREATE TABLE VCD_METADATATYPES
( TYPE_ID NUMBER(10,0) NOT NULL,
TYPE_NAME VARCHAR2(50) NOT NULL,
TYPE_DESCRIPTION VARCHAR2(150) NOT NULL,
OWNER_ID NUMBER(10,0) NOT NULL
)
/
CREATE TABLE VCD_LOG
( EVENT_ID NUMBER(10,0) NOT NULL,
MESSAGE VARCHAR2(200) NOT NULL,
USER_ID NUMBER(10,0),
EVENT_DATE DATE NOT NULL,
IP VARCHAR2(15) NOT NULL
)
/

CREATE OR REPLACE TRIGGER VCD_COMMENTS_BEF_INS
before insert on vcd_comments for each row
declare
curr_id integer := 0;
begin
if (:old.comment_id is null)
then
select nvl(max(comment_id),0) into curr_id from vcd_comments;
:new.comment_id := curr_id + 1;
end if;
end vcd_comments_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_METADATATYPES_BEF_INS
before insert on vcd_metadatatypes for each row
declare
curr_id integer := 0;
begin
if (:old.type_id is null)
then
select nvl(max(type_id),0) into curr_id from vcd_metadatatypes;
:new.type_id := curr_id + 1;
end if;
end vcd_metadatatypes_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_METADATA_BEF_INS
before insert on vcd_metadata for each row
declare
curr_id integer := 0;
begin
if (:old.metadata_id is null)
then
select nvl(max(metadata_id),0) into curr_id from vcd_metadata;
:new.metadata_id := curr_id + 1;
end if;
end vcd_metadata_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_RSSFEEDS_BEF_INS
before insert on vcd_rssfeeds for each row
declare
curr_id integer := 0;
begin
if (:old.feed_id is null)
then
select nvl(max(feed_id),0) into curr_id from vcd_rssfeeds;
:new.feed_id := curr_id + 1;
end if;
end vcd_rssfeeds_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_BORROWERS_BEF_INS
before insert on vcd_borrowers for each row
declare
curr_id integer := 0;
begin
if (:old.borrower_id is null)
then
select nvl(max(borrower_id),0) into curr_id from vcd_borrowers;
:new.borrower_id := curr_id + 1;
end if;
end vcd_borrowers_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_BEF_INS
before insert on vcd for each row
declare
curr_id integer := 0;
begin
if (:old.vcd_id is null)
then
select nvl(max(vcd_id),0) into curr_id from vcd;
:new.vcd_id := curr_id + 1;
end if;
end vcd_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_USERLOANS_BEF_INS
before insert on vcd_userloans for each row
declare
curr_id integer := 0;
begin
if (:old.loan_id is null)
then
select nvl(max(loan_id),0) into curr_id from vcd_userloans;
:new.loan_id := curr_id + 1;
end if;
end vcd_userloans_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_PORNSTUDIOS_BEF_INS
before insert on vcd_pornstudios for each row
declare
curr_id integer := 0;
begin
if (:old.studio_id is null)
then
select nvl(max(studio_id),0) into curr_id from vcd_pornstudios;
:new.studio_id := curr_id + 1;
end if;
end vcd_pornstudios_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_PORNCATEGORIES_BEF_INS
before insert on vcd_porncategories for each row
declare
curr_id integer := 0;
begin
if (:old.category_id is null)
then
select nvl(max(category_id),0) into curr_id from vcd_porncategories;
:new.category_id := curr_id + 1;
end if;
end vcd_porncategories_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_COVERS_BEF_INS
before insert on vcd_covers for each row
declare
curr_id integer := 0;
begin
if (:old.cover_id is null)
then
select nvl(max(cover_id),0) into curr_id from vcd_covers;
:new.cover_id := curr_id + 1;
end if;
end vcd_covers_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_MOVIECATEGORIES_BEF_INS
before insert on vcd_moviecategories for each row
declare
curr_id integer := 0;
begin
if (:old.category_id is null)
then
select nvl(max(category_id),0) into curr_id from vcd_moviecategories;
:new.category_id := curr_id + 1;
end if;
end vcd_moviecategories_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_PORNSTARS_BEF_INS
before insert on vcd_pornstars for each row
declare
curr_id integer := 0;
begin
if (:old.pornstar_id is null)
then
select nvl(max(pornstar_id),0) into curr_id from vcd_pornstars;
:new.pornstar_id := curr_id + 1;
end if;
end vcd_pornstars_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_IMAGES_BEF_INS
before insert on vcd_images for each row
declare
curr_id integer := 0;
begin
if (:old.image_id is null)
then
select nvl(max(image_id),0) into curr_id from vcd_images;
:new.image_id := curr_id + 1;
end if;
end vcd_images_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_SOURCESITES_BEF_INS
before insert on vcd_sourcesites for each row
declare
curr_id integer := 0;
begin
if (:old.site_id is null)
then
select nvl(max(site_id),0) into curr_id from vcd_sourcesites;
:new.site_id := curr_id + 1;
end if;
end vcd_usersourcesites_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_USERPROPERTIES_BEF_INS
before insert on vcd_userproperties for each row
declare
curr_id integer := 0;
begin
if (:old.property_id is null)
then
select nvl(max(property_id),0) into curr_id from vcd_userproperties;
:new.property_id := curr_id + 1;
end if;
end vcd_userproperties_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_USERS_BEF_INS
before insert on vcd_users for each row
declare
curr_id integer := 0;
begin
if (:old.user_id is null)
then
select nvl(max(user_id),0) into curr_id from vcd_users;
:new.user_id := curr_id + 1;
end if;
end vcd_users_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_USERROLES_BEF_INS
before insert on vcd_userroles for each row
declare
curr_id integer := 0;
begin
if (:old.role_id is null)
then
select nvl(max(role_id),0) into curr_id from vcd_userroles;
:new.role_id := curr_id + 1;
end if;
end vcd_userroles_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_COVERTYPES_BEF_INS
before insert on vcd_covertypes for each row
declare
curr_id integer := 0;
begin
if (:old.cover_type_id is null)
then
select nvl(max(cover_type_id),0) into curr_id from vcd_covertypes;
:new.cover_type_id := curr_id + 1;
end if;
end vcd_covertypes_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_SETTINGS_BEF_INS
before insert on vcd_settings for each row
declare
curr_id integer := 0;
begin
if (:old.settings_id is null)
then
select nvl(max(settings_id),0) into curr_id from vcd_settings;
:new.settings_id := curr_id + 1;
end if;
end vcd_settings_bef_ins;
/

CREATE OR REPLACE TRIGGER VCD_MEDIATYPES_BEF_INS
before insert on vcd_mediatypes for each row
declare
curr_id integer := 0;
begin
if (:old.media_type_id is null)
then
select nvl(max(media_type_id),0) into curr_id from vcd_mediatypes;
:new.media_type_id := curr_id + 1;
end if;
end vcd_mediatypes_bef_ins;
/