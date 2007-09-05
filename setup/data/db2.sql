CREATE TABLE vcd (
        vcd_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        title VARCHAR (150) NOT NULL ,
        category_id INTEGER NOT NULL ,
        year INTEGER,
        PRIMARY KEY (vcd_id)
) 
GO

CREATE TABLE vcd_Borrowers (
        borrower_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        owner_id INTEGER NOT NULL ,
        name VARCHAR (100) NOT NULL ,
        email VARCHAR (50),
        PRIMARY KEY (borrower_id)
) 
GO
CREATE TABLE vcd_Comments (
        comment_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        vcd_id INTEGER NOT NULL ,
        user_id INTEGER NOT NULL ,
        comment_date DATE NOT NULL ,
        comment LONG VARCHAR NOT NULL ,
        isPrivate SMALLINT NOT NULL,
        PRIMARY KEY (comment_id)
)  
GO

CREATE TABLE vcd_CoverTypes (
        cover_type_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        cover_type_name VARCHAR (50) NOT NULL ,
        cover_type_description VARCHAR (100),
        PRIMARY KEY (cover_type_id)
) 
GO

CREATE TABLE vcd_Covers (
        cover_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        vcd_id INTEGER NOT NULL ,
        cover_type_id INTEGER NOT NULL ,
        cover_filename VARCHAR (100) NOT NULL ,
        cover_filesize INTEGER,
        user_id INTEGER NOT NULL ,
        date_added DATE NOT NULL ,
        image_id INTEGER,
        PRIMARY KEY (cover_id)
) 

GO
CREATE TABLE vcd_CoversAllowedOnMediatypes (
        cover_type_id INTEGER NOT NULL ,
        media_type_id INTEGER NOT NULL
) 

GO
CREATE TABLE vcd_IMDB (
        imdb VARCHAR (32) NOT NULL ,
        title VARCHAR (200) NOT NULL ,
        alt_title1 VARCHAR (200) ,
        alt_title2 VARCHAR (160) ,
        image VARCHAR (100) ,
        year INTEGER  ,
        plot LONG VARCHAR ,
        director VARCHAR (100) ,
        fcast LONG VARCHAR ,
        rating VARCHAR (5) ,
        runtime INTEGER,
        country VARCHAR (80),
        genre VARCHAR (100),
        PRIMARY KEY (imdb)
)  

GO
CREATE TABLE vcd_Images (
        image_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        name VARCHAR (80) NOT NULL ,
        image_type VARCHAR (12) NOT NULL ,
        image_size INTEGER NOT NULL ,
        image BLOB (1000K),
        PRIMARY KEY (image_id)
)  
GO

CREATE TABLE vcd_MediaTypes (
        media_type_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        media_type_name VARCHAR (50) NOT NULL ,
        parent_id INTEGER ,
        media_type_description VARCHAR (100) ,
        PRIMARY KEY (media_type_id)
) 

GO
CREATE TABLE vcd_MovieCategories (
        category_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        category_name VARCHAR (100) NOT NULL,
        PRIMARY KEY (category_id)
) 
GO

CREATE TABLE vcd_PornCategories (
        category_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        category_name VARCHAR (50) NOT NULL,
        PRIMARY KEY (category_id)
) 

GO
CREATE TABLE vcd_PornStudios (
        studio_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        studio_name VARCHAR (150) NOT NULL,
        PRIMARY KEY (studio_id)
) 
GO

CREATE TABLE vcd_Pornstars (
        pornstar_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        name VARCHAR (150) NOT NULL ,
        homepage VARCHAR (100) ,
        image_name VARCHAR (100),
        biography LONG VARCHAR ,
        PRIMARY KEY (pornstar_id)
)  
GO

CREATE TABLE vcd_PropertiesToUser (
        user_id INTEGER NOT NULL ,
        property_id INTEGER NOT NULL
) 
GO

CREATE TABLE vcd_Screenshots (
        vcd_id INTEGER NOT NULL
) 
GO

CREATE TABLE vcd_Sessions (
        session_id VARCHAR (50) NOT NULL ,
        session_user_id INTEGER NOT NULL ,
        session_start INTEGER ,
        session_ip VARCHAR (15) NOT NULL
) 
GO

CREATE TABLE vcd_Settings (
        settings_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        settings_key VARCHAR (50) NOT NULL ,
        settings_value VARCHAR (255) NOT NULL ,
        settings_description VARCHAR (255)  ,
        isProtected SMALLINT NOT NULL ,
        settings_type VARCHAR (20) 
) 
GO

CREATE TABLE vcd_SourceSites (
        site_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        site_name VARCHAR (50) NOT NULL ,
        site_alias VARCHAR (50)  ,
        site_homepage VARCHAR (80) NOT NULL ,
        site_getCommand VARCHAR (100)  ,
        site_isFetchable SMALLINT NOT NULL,
        site_classname VARCHAR (20)  ,
        site_image VARCHAR (50) 
) 
GO

CREATE TABLE vcd_UserLoans (
        loan_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        vcd_id INTEGER NOT NULL ,
        owner_id INTEGER NOT NULL ,
        borrower_id INTEGER NOT NULL ,
        date_out DATE NOT NULL ,
        date_in DATE ,
        PRIMARY KEY(loan_id)
) 
GO

CREATE TABLE vcd_UserProperties (
        property_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        property_name VARCHAR (50) NOT NULL ,
        property_description VARCHAR (80) 
) 
GO

CREATE TABLE vcd_UserRoles (
        role_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        role_name VARCHAR (50) NOT NULL ,
        role_description VARCHAR (100) ,
        PRIMARY KEY(role_id)
) 
GO

CREATE TABLE vcd_UserWishList (
        user_id INTEGER NOT NULL ,
        vcd_id INTEGER NOT NULL
) 
GO

CREATE TABLE vcd_Users (
        user_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        user_name VARCHAR (80) NOT NULL ,
        user_password VARCHAR (255) NOT NULL ,
        user_fullname VARCHAR (100) NOT NULL ,
        user_email VARCHAR (80) NOT NULL ,
        role_id INTEGER NOT NULL ,
        is_deleted SMALLINT NOT NULL ,
        date_created DATE NOT NULL,
        PRIMARY KEY(user_id)
) 

GO

CREATE TABLE vcd_VcdToPornCategories (
        vcd_id INTEGER NOT NULL ,
        category_id INTEGER NOT NULL
) 
 
GO

CREATE TABLE vcd_VcdToPornStudios (
        vcd_id INTEGER NOT NULL , 
        studio_id INTEGER NOT NULL
) 

GO

CREATE TABLE vcd_VcdToPornstars (
        vcd_id INTEGER NOT NULL ,
        pornstar_id INTEGER NOT NULL
) 
GO

CREATE TABLE vcd_VcdToSources (
        vcd_id INTEGER NOT NULL ,
        site_id INTEGER NOT NULL ,
        external_id VARCHAR (32) NOT NULL
) 
GO

CREATE TABLE vcd_RssFeeds (
        feed_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        user_id INTEGER NOT NULL ,
        feed_name VARCHAR (60) NOT NULL ,
        feed_url VARCHAR (150) NOT NULL ,
        isadult SMALLINT,
        issite SMALLINT,
        PRIMARY KEY(feed_id)
) 
GO

CREATE TABLE vcd_MetaData (
        metadata_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        record_id INTEGER NOT NULL ,
        mediatype_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL ,
        type_id INTEGER NOT NULL ,
        metadata_value VARCHAR (250) NOT NULL,
        PRIMARY KEY(metadata_id)
) 
GO

CREATE TABLE vcd_MetaDataTypes (
		type_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        type_name VARCHAR (50) NOT NULL ,
        type_description VARCHAR (150),
        owner_id SMALLINT NOT NULL,
        PRIMARY KEY(type_id)
) 
GO

CREATE TABLE vcd_VcdToUsers (
        vcd_id INTEGER NOT NULL ,
        user_id INTEGER NOT NULL ,
        media_type_id INTEGER NOT NULL ,
        disc_count INTEGER NOT NULL ,
        date_added DATE NOT NULL
)
GO

CREATE TABLE vcd_Log (
		event_id SMALLINT NOT NULL ,
		message VARCHAR (200) NOT NULL ,
		user_id INTEGER ,
		event_date DATE NOT NULL ,
		ip VARCHAR (15) NOT NULL
)

