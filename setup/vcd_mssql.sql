if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_Comments_vcd]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Comments] DROP CONSTRAINT FK_vcd_Comments_vcd


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_Covers_vcd]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Covers] DROP CONSTRAINT FK_vcd_Covers_vcd


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToPornCategories_vcd]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToPornCategories] DROP CONSTRAINT FK_vcd_VcdToPornCategories_vcd


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToPornstars_vcd]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToPornstars] DROP CONSTRAINT FK_vcd_VcdToPornstars_vcd


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToPornStudios_vcd]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToPornStudios] DROP CONSTRAINT FK_vcd_VcdToPornStudios_vcd


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToSources_vcd]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToSources] DROP CONSTRAINT FK_vcd_VcdToSources_vcd


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToUsers_vcd]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToUsers] DROP CONSTRAINT FK_vcd_VcdToUsers_vcd


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_covers_vcd_covertypes]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Covers] DROP CONSTRAINT FK_vcd_covers_vcd_covertypes


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_CoversAllowedOnMediatypes_vcd_CoverTypes]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_CoversAllowedOnMediatypes] DROP CONSTRAINT FK_vcd_CoversAllowedOnMediatypes_vcd_CoverTypes


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToSources_vcd_IMDB]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToSources] DROP CONSTRAINT FK_vcd_VcdToSources_vcd_IMDB


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_Covers_vcd_Images]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Covers] DROP CONSTRAINT FK_vcd_Covers_vcd_Images


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_CoversAllowedOnMediatypes_vcd_MediaTypes]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_CoversAllowedOnMediatypes] DROP CONSTRAINT FK_vcd_CoversAllowedOnMediatypes_vcd_MediaTypes


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_MediaTypes_vcd_MediaTypes]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_MediaTypes] DROP CONSTRAINT FK_vcd_MediaTypes_vcd_MediaTypes


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToUsers_vcd_MediaTypes]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToUsers] DROP CONSTRAINT FK_vcd_VcdToUsers_vcd_MediaTypes


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_vcd_MovieCategories]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd] DROP CONSTRAINT FK_vcd_vcd_MovieCategories


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToPornCategories_vcd_PornCategories]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToPornCategories] DROP CONSTRAINT FK_vcd_VcdToPornCategories_vcd_PornCategories


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToPornStudios_vcd_PornStudios]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToPornStudios] DROP CONSTRAINT FK_vcd_VcdToPornStudios_vcd_PornStudios


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToPornstars_vcd_Pornstars]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToPornstars] DROP CONSTRAINT FK_vcd_VcdToPornstars_vcd_Pornstars


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToSources_vcd_SourceSites]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToSources] DROP CONSTRAINT FK_vcd_VcdToSources_vcd_SourceSites


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_PropertiesToUser_vcd_UserProperties]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_PropertiesToUser] DROP CONSTRAINT FK_vcd_PropertiesToUser_vcd_UserProperties


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_users_vcd_userroles]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Users] DROP CONSTRAINT FK_vcd_users_vcd_userroles


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_Borrowers_vcd_Users]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Borrowers] DROP CONSTRAINT FK_vcd_Borrowers_vcd_Users


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_Comments_vcd_Users]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Comments] DROP CONSTRAINT FK_vcd_Comments_vcd_Users


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_Covers_vcd_Users]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Covers] DROP CONSTRAINT FK_vcd_Covers_vcd_Users


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_PropertiesToUser_vcd_Users]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_PropertiesToUser] DROP CONSTRAINT FK_vcd_PropertiesToUser_vcd_Users


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_Sessions_vcd_Users]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_Sessions] DROP CONSTRAINT FK_vcd_Sessions_vcd_Users


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_UserWishList_vcd_Users]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_UserWishList] DROP CONSTRAINT FK_vcd_UserWishList_vcd_Users


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[FK_vcd_VcdToUsers_vcd_Users]') and OBJECTPROPERTY(id, N'IsForeignKey') = 1)
ALTER TABLE [dbo].[vcd_VcdToUsers] DROP CONSTRAINT FK_vcd_VcdToUsers_vcd_Users


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Borrowers]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Borrowers]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Comments]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Comments]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_CoverTypes]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_CoverTypes]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Covers]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Covers]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_CoversAllowedOnMediatypes]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_CoversAllowedOnMediatypes]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_IMDB]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_IMDB]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Images]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Images]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_MediaTypes]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_MediaTypes]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_MovieCategories]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_MovieCategories]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_PornCategories]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_PornCategories]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_PornStudios]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_PornStudios]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Pornstars]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Pornstars]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_PropertiesToUser]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_PropertiesToUser]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Screenshots]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Screenshots]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Sessions]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Sessions]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Settings]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Settings]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_SourceSites]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_SourceSites]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_UserLoans]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_UserLoans]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_UserProperties]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_UserProperties]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_UserRoles]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_UserRoles]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_UserWishList]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_UserWishList]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_Users]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_Users]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_VcdToPornCategories]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_VcdToPornCategories]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_VcdToPornStudios]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_VcdToPornStudios]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_VcdToPornstars]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_VcdToPornstars]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_VcdToSources]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_VcdToSources]


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[vcd_VcdToUsers]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[vcd_VcdToUsers]


CREATE TABLE [dbo].[vcd] (
        [vcd_id] [int] IDENTITY (1, 1) NOT NULL ,
        [title] [varchar] (150) NOT NULL ,
        [category_id] [int] NOT NULL ,
        [year] [int] NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Borrowers] (
        [borrower_id] [int] IDENTITY (1, 1) NOT NULL ,
        [owner_id] [int] NOT NULL ,
        [name] [varchar] (100) NOT NULL ,
        [email] [varchar] (50) NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Comments] (
        [comment_id] [int] IDENTITY (1, 1) NOT NULL ,
        [vcd_id] [int] NOT NULL ,
        [user_id] [int] NOT NULL ,
        [comment_date] [datetime] NOT NULL ,
        [comment] [text] NOT NULL ,
        [isPrivate] [bit] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


CREATE TABLE [dbo].[vcd_CoverTypes] (
        [cover_type_id] [int] IDENTITY (1, 1) NOT NULL ,
        [cover_type_name] [varchar] (50) NOT NULL ,
        [cover_type_description] [varchar] (100) NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Covers] (
        [cover_id] [int] IDENTITY (1, 1) NOT NULL ,
        [vcd_id] [int] NOT NULL ,
        [cover_type_id] [int] NOT NULL ,
        [cover_filename] [varchar] (100) NOT NULL ,
        [cover_filesize] [int] NULL ,
        [user_id] [int] NOT NULL ,
        [date_added] [datetime] NOT NULL ,
        [image_id] [int] NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_CoversAllowedOnMediatypes] (
        [cover_type_id] [int] NOT NULL ,
        [media_type_id] [int] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_IMDB] (
        [imdb] [varchar] (32) NOT NULL ,
        [title] [varchar] (200) NOT NULL ,
        [alt_title1] [varchar] (200) NULL ,
        [alt_title2] [varchar] (160) NULL ,
        [image] [varchar] (100) NULL ,
        [year] [int] NULL ,
        [plot] [text] NULL ,
        [director] [varchar] (100) NULL ,
        [fcast] [text] NULL ,
        [rating] [varchar] (5) NULL ,
        [runtime] [int] NULL ,
        [country] [varchar] (80) NULL ,
        [genre] [varchar] (100) NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Images] (
        [image_id] [int] IDENTITY (1, 1) NOT NULL ,
        [name] [varchar] (80) NOT NULL ,
        [image_type] [varchar] (12) NOT NULL ,
        [image_size] [int] NOT NULL ,
        [image] [image] NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


CREATE TABLE [dbo].[vcd_MediaTypes] (
        [media_type_id] [int] IDENTITY (1, 1) NOT NULL ,
        [media_type_name] [varchar] (50) NOT NULL ,
        [parent_id] [int] NULL ,
        [media_type_description] [varchar] (100) NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_MovieCategories] (
        [category_id] [int] IDENTITY (1, 1) NOT NULL ,
        [category_name] [varchar] (100) NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_PornCategories] (
        [category_id] [int] IDENTITY (1, 1) NOT NULL ,
        [category_name] [varchar] (50) NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_PornStudios] (
        [studio_id] [int] IDENTITY (1, 1) NOT NULL ,
        [studio_name] [varchar] (150) NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Pornstars] (
        [pornstar_id] [int] IDENTITY (1, 1) NOT NULL ,
        [name] [varchar] (150) NOT NULL ,
        [homepage] [varchar] (100) NULL ,
        [image_name] [varchar] (100) NULL ,
        [biography] [text] NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


CREATE TABLE [dbo].[vcd_PropertiesToUser] (
        [user_id] [int] NOT NULL ,
        [property_id] [int] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Screenshots] (
        [vcd_id] [int] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Sessions] (
        [session_id] [varchar] (50) NOT NULL ,
        [session_user_id] [int] NOT NULL ,
        [session_start] [int] NULL ,
        [session_ip] [varchar] (15) NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Settings] (
        [settings_id] [int] IDENTITY (1, 1) NOT NULL ,
        [settings_key] [varchar] (50) NOT NULL ,
        [settings_value] [varchar] (255) NOT NULL ,
        [settings_description] [varchar] (255) NULL ,
        [isProtected] [bit] NOT NULL ,
        [settings_type] [varchar] (20) NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_SourceSites] (
        [site_id] [int] IDENTITY (1, 1) NOT NULL ,
        [site_name] [varchar] (50) NOT NULL ,
        [site_alias] [varchar] (50) NULL ,
        [site_homepage] [varchar] (80) NOT NULL ,
        [site_getCommand] [varchar] (100) NULL ,
        [site_isFetchable] [bit] NOT NULL,
        [site_classname] [varchar] (20) NULL ,
        [site_image] [varchar] (50) NULL 
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_UserLoans] (
        [loan_id] [int] IDENTITY (1, 1) NOT NULL ,
        [vcd_id] [int] NOT NULL ,
        [owner_id] [int] NOT NULL ,
        [borrower_id] [int] NOT NULL ,
        [date_out] [datetime] NOT NULL ,
        [date_in] [datetime] NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_UserProperties] (
        [property_id] [int] IDENTITY (1, 1) NOT NULL ,
        [property_name] [varchar] (50) NOT NULL ,
        [property_description] [varchar] (80) NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_UserRoles] (
        [role_id] [int] IDENTITY (1, 1) NOT NULL ,
        [role_name] [varchar] (50) NOT NULL ,
        [role_description] [varchar] (100) NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_UserWishList] (
        [user_id] [int] NOT NULL ,
        [vcd_id] [int] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_Users] (
        [user_id] [int] IDENTITY (1, 1) NOT NULL ,
        [user_name] [varchar] (80) NOT NULL ,
        [user_password] [varchar] (255) NOT NULL ,
        [user_fullname] [varchar] (100) NOT NULL ,
        [user_email] [varchar] (80) NOT NULL ,
        [role_id] [int] NOT NULL ,
        [is_deleted] [bit] NOT NULL ,
        [date_created] [datetime] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_VcdToPornCategories] (
        [vcd_id] [int] NOT NULL ,
        [category_id] [int] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_VcdToPornStudios] (
        [vcd_id] [int] NOT NULL ,
        [studio_id] [int] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_VcdToPornstars] (
        [vcd_id] [int] NOT NULL ,
        [pornstar_id] [int] NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_VcdToSources] (
        [vcd_id] [int] NOT NULL ,
        [site_id] [int] NOT NULL ,
        [external_id] [varchar] (32) NOT NULL
) ON [PRIMARY]


CREATE TABLE [dbo].[vcd_VcdToUsers] (
        [vcd_id] [int] NOT NULL ,
        [user_id] [int] NOT NULL ,
        [media_type_id] [int] NOT NULL ,
        [disc_count] [int] NOT NULL ,
        [date_added] [datetime] NOT NULL
) ON [PRIMARY]

CREATE TABLE [vcd_RssFeeds] (
	[feed_id] [int] IDENTITY (1, 1) NOT NULL ,
	[user_id] [int] NOT NULL ,
	[feed_name] [varchar] (60) NOT NULL ,
	[feed_url] [varchar] (150) NOT NULL 	
) ON [PRIMARY]


CREATE TABLE [vcd_MetaData] (
	[metadata_id] [int] IDENTITY (1, 1) NOT NULL ,
	[record_id] [int] NOT NULL ,
	[mediatype_id] [int] NOT NULL ,
	[user_id] [int] NOT NULL ,
	[type_id] [int] NOT NULL ,
	[metadata_value] [varchar] (150) NOT NULL
) ON [PRIMARY]

CREATE TABLE [vcd_MetaDataTypes] (
	[type_id] [int] IDENTITY (1, 1) NOT NULL ,
	[type_name] [varchar] (50) NOT NULL ,
	[type_description] [varchar] (150) NULL ,
	[owner_id] [int] NOT NULL
) ON [PRIMARY]

CREATE TABLE [vcd_Log] (
	[event_id] [tinyint] NOT NULL ,
	[message] [varchar] (200) NOT NULL ,
	[user_id] [int] NULL ,
	[event_date] [datetime] NOT NULL ,
	[ip] [char] (15) NOT NULL 
) ON [PRIMARY]


ALTER TABLE [dbo].[vcd_MetaDataTypes] WITH NOCHECK ADD
		CONSTRAINT [PK_vcd_MetaDataTypes] PRIMARY KEY  CLUSTERED 
			(
				[type_id]
			)  ON [PRIMARY] 


ALTER TABLE [dbo].[vcd_RssFeeds] WITH NOCHECK ADD
		CONSTRAINT [PK_vcd_RssFeeds] PRIMARY KEY  CLUSTERED 
			(
				[feed_id]
			)  ON [PRIMARY] 

ALTER TABLE [dbo].[vcd] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd] PRIMARY KEY  CLUSTERED
        (
                [vcd_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Borrowers] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_Borrowers] PRIMARY KEY  CLUSTERED
        (
                [borrower_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Comments] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_Comments] PRIMARY KEY  CLUSTERED
        (
                [comment_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_CoverTypes] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_covertypes] PRIMARY KEY  CLUSTERED
        (
                [cover_type_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Covers] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_covers] PRIMARY KEY  CLUSTERED
        (
                [cover_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_CoversAllowedOnMediatypes] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_CoversAllowedOnMediatypes] PRIMARY KEY  CLUSTERED
        (
                [cover_type_id],
                [media_type_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_IMDB] WITH NOCHECK ADD
        CONSTRAINT [DF__MovieInfo__title__6477ECF3] DEFAULT ('0') FOR [title],
        CONSTRAINT [DF__MovieInfo__alt_t__656C112C] DEFAULT (null) FOR [alt_title1],
        CONSTRAINT [DF__MovieInfo__alt_t__66603565] DEFAULT (null) FOR [alt_title2],
        CONSTRAINT [DF__MovieInfo__image__6754599E] DEFAULT (null) FOR [image],
        CONSTRAINT [DF__MovieInfo__year__68487DD7] DEFAULT (null) FOR [year],
        CONSTRAINT [DF__MovieInfo__direc__693CA210] DEFAULT (null) FOR [director],
        CONSTRAINT [DF__MovieInfo__ratin__6A30C649] DEFAULT (null) FOR [rating],
        CONSTRAINT [DF__MovieInfo__runti__6B24EA82] DEFAULT (null) FOR [runtime],
        CONSTRAINT [DF__MovieInfo__count__6E01572D] DEFAULT (null) FOR [country],
        CONSTRAINT [DF__MovieInfo__genre__6EF57B66] DEFAULT (null) FOR [genre],
        CONSTRAINT [PK_MovieInfo] PRIMARY KEY  CLUSTERED
        (
                [imdb]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Images] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_Images] PRIMARY KEY  CLUSTERED
        (
                [image_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_MediaTypes] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_mediatypes] PRIMARY KEY  CLUSTERED
        (
                [media_type_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_MovieCategories] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_MovieCategories] PRIMARY KEY  CLUSTERED
        (
                [category_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_PornCategories] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_PornCategories] PRIMARY KEY  CLUSTERED
        (
                [category_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_PornStudios] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_PornStudios] PRIMARY KEY  CLUSTERED
        (
                [studio_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Pornstars] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_Pornstars] PRIMARY KEY  CLUSTERED
        (
                [pornstar_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_PropertiesToUser] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_PropertiesToUser] PRIMARY KEY  CLUSTERED
        (
                [user_id],
                [property_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Screenshots] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_Screenshots] PRIMARY KEY  CLUSTERED
        (
                [vcd_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Settings] WITH NOCHECK ADD
        CONSTRAINT [DF_vcd_Settings_isCore] DEFAULT (1) FOR [isProtected],
        CONSTRAINT [PK_vcd_settings] PRIMARY KEY  CLUSTERED
        (
                [settings_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_SourceSites] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_SourceSites] PRIMARY KEY  CLUSTERED
        (
                [site_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_UserLoans] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_UserLoans] PRIMARY KEY  CLUSTERED
        (
                [loan_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_UserProperties] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_userProperties] PRIMARY KEY  CLUSTERED
        (
                [property_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_UserRoles] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_userroles] PRIMARY KEY  CLUSTERED
        (
                [role_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_UserWishList] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_UserWishList] PRIMARY KEY  CLUSTERED
        (
                [user_id],
                [vcd_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_Users] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_users] PRIMARY KEY  CLUSTERED
        (
                [user_id]
        )  ON [PRIMARY]


        
ALTER TABLE [dbo].[vcd_VcdToPornCategories] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_VcdToPornCategories] PRIMARY KEY  CLUSTERED
        (
                [vcd_id],
                [category_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_VcdToPornStudios] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_VcdToPornStudios] PRIMARY KEY  CLUSTERED
        (
                [vcd_id],
                [studio_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_VcdToPornstars] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_VcdToPornstars] PRIMARY KEY  CLUSTERED
        (
                [vcd_id],
                [pornstar_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd_VcdToSources] WITH NOCHECK ADD
        CONSTRAINT [PK_vcd_VcdToSources] PRIMARY KEY  CLUSTERED
        (
                [vcd_id],
                [site_id]
        )  ON [PRIMARY]


ALTER TABLE [dbo].[vcd] ADD
        CONSTRAINT [FK_vcd_vcd_MovieCategories] FOREIGN KEY
        (
                [category_id]
        ) REFERENCES [dbo].[vcd_MovieCategories] (
                [category_id]
        )


ALTER TABLE [dbo].[vcd_Borrowers] ADD
        CONSTRAINT [FK_vcd_Borrowers_vcd_Users] FOREIGN KEY
        (
                [owner_id]
        ) REFERENCES [dbo].[vcd_Users] (
                [user_id]
        )


ALTER TABLE [dbo].[vcd_Comments] ADD
        CONSTRAINT [FK_vcd_Comments_vcd] FOREIGN KEY
        (
                [vcd_id]
        ) REFERENCES [dbo].[vcd] (
                [vcd_id]
        ),
        CONSTRAINT [FK_vcd_Comments_vcd_Users] FOREIGN KEY
        (
                [user_id]
        ) REFERENCES [dbo].[vcd_Users] (
                [user_id]
        )


ALTER TABLE [dbo].[vcd_Covers] ADD
        CONSTRAINT [FK_vcd_Covers_vcd] FOREIGN KEY
        (
                [vcd_id]
        ) REFERENCES [dbo].[vcd] (
                [vcd_id]
        ),
        CONSTRAINT [FK_vcd_covers_vcd_covertypes] FOREIGN KEY
        (
                [cover_type_id]
        ) REFERENCES [dbo].[vcd_CoverTypes] (
                [cover_type_id]
        ),
        CONSTRAINT [FK_vcd_Covers_vcd_Images] FOREIGN KEY
        (
                [image_id]
        ) REFERENCES [dbo].[vcd_Images] (
                [image_id]
        ),
        CONSTRAINT [FK_vcd_Covers_vcd_Users] FOREIGN KEY
        (
                [user_id]
        ) REFERENCES [dbo].[vcd_Users] (
                [user_id]
        )


ALTER TABLE [dbo].[vcd_CoversAllowedOnMediatypes] ADD
        CONSTRAINT [FK_vcd_CoversAllowedOnMediatypes_vcd_CoverTypes] FOREIGN KEY
        (
                [cover_type_id]
        ) REFERENCES [dbo].[vcd_CoverTypes] (
                [cover_type_id]
        ),
        CONSTRAINT [FK_vcd_CoversAllowedOnMediatypes_vcd_MediaTypes] FOREIGN KEY
        (
                [media_type_id]
        ) REFERENCES [dbo].[vcd_MediaTypes] (
                [media_type_id]
        )


ALTER TABLE [dbo].[vcd_MediaTypes] ADD
        CONSTRAINT [FK_vcd_MediaTypes_vcd_MediaTypes] FOREIGN KEY
        (
                [parent_id]
        ) REFERENCES [dbo].[vcd_MediaTypes] (
                [media_type_id]
        )


alter table [dbo].[vcd_MediaTypes] nocheck constraint [FK_vcd_MediaTypes_vcd_MediaTypes]


ALTER TABLE [dbo].[vcd_PropertiesToUser] ADD
        CONSTRAINT [FK_vcd_PropertiesToUser_vcd_UserProperties] FOREIGN KEY
        (
                [property_id]
        ) REFERENCES [dbo].[vcd_UserProperties] (
                [property_id]
        ),
        CONSTRAINT [FK_vcd_PropertiesToUser_vcd_Users] FOREIGN KEY
        (
                [user_id]
        ) REFERENCES [dbo].[vcd_Users] (
                [user_id]
        )


ALTER TABLE [dbo].[vcd_Sessions] ADD
        CONSTRAINT [FK_vcd_Sessions_vcd_Users] FOREIGN KEY
        (
                [session_user_id]
        ) REFERENCES [dbo].[vcd_Users] (
                [user_id]
        )


ALTER TABLE [dbo].[vcd_UserWishList] ADD
        CONSTRAINT [FK_vcd_UserWishList_vcd_Users] FOREIGN KEY
        (
                [user_id]
        ) REFERENCES [dbo].[vcd_Users] (
                [user_id]
        )


ALTER TABLE [dbo].[vcd_Users] ADD
        CONSTRAINT [FK_vcd_users_vcd_userroles] FOREIGN KEY
        (
                [role_id]
        ) REFERENCES [dbo].[vcd_UserRoles] (
                [role_id]
        )


ALTER TABLE [dbo].[vcd_VcdToPornCategories] ADD
        CONSTRAINT [FK_vcd_VcdToPornCategories_vcd] FOREIGN KEY
        (
                [vcd_id]
        ) REFERENCES [dbo].[vcd] (
                [vcd_id]
        ),
        CONSTRAINT [FK_vcd_VcdToPornCategories_vcd_PornCategories] FOREIGN KEY
        (
                [category_id]
        ) REFERENCES [dbo].[vcd_PornCategories] (
                [category_id]
        )


ALTER TABLE [dbo].[vcd_VcdToPornStudios] ADD
        CONSTRAINT [FK_vcd_VcdToPornStudios_vcd] FOREIGN KEY
        (
                [vcd_id]
        ) REFERENCES [dbo].[vcd] (
                [vcd_id]
        ),
        CONSTRAINT [FK_vcd_VcdToPornStudios_vcd_PornStudios] FOREIGN KEY
        (
                [studio_id]
        ) REFERENCES [dbo].[vcd_PornStudios] (
                [studio_id]
        )


ALTER TABLE [dbo].[vcd_VcdToPornstars] ADD
        CONSTRAINT [FK_vcd_VcdToPornstars_vcd] FOREIGN KEY
        (
                [vcd_id]
        ) REFERENCES [dbo].[vcd] (
                [vcd_id]
        ),
        CONSTRAINT [FK_vcd_VcdToPornstars_vcd_Pornstars] FOREIGN KEY
        (
                [pornstar_id]
        ) REFERENCES [dbo].[vcd_Pornstars] (
                [pornstar_id]
        )


ALTER TABLE [dbo].[vcd_VcdToSources] ADD
        CONSTRAINT [FK_vcd_VcdToSources_vcd] FOREIGN KEY
        (
                [vcd_id]
        ) REFERENCES [dbo].[vcd] (
                [vcd_id]
        ),
        CONSTRAINT [FK_vcd_VcdToSources_vcd_IMDB] FOREIGN KEY
        (
                [external_id]
        ) REFERENCES [dbo].[vcd_IMDB] (
                [imdb]
        ),
        CONSTRAINT [FK_vcd_VcdToSources_vcd_SourceSites] FOREIGN KEY
        (
                [site_id]
        ) REFERENCES [dbo].[vcd_SourceSites] (
                [site_id]
        )


ALTER TABLE [dbo].[vcd_VcdToSources] nocheck CONSTRAINT [FK_vcd_VcdToSources_vcd_IMDB]


ALTER TABLE [dbo].[vcd_VcdToUsers] ADD
        CONSTRAINT [FK_vcd_VcdToUsers_vcd] FOREIGN KEY
        (
                [vcd_id]
        ) REFERENCES [dbo].[vcd] (
                [vcd_id]
        ),
        CONSTRAINT [FK_vcd_VcdToUsers_vcd_MediaTypes] FOREIGN KEY
        (
                [media_type_id]
        ) REFERENCES [dbo].[vcd_MediaTypes] (
                [media_type_id]
        ),
        CONSTRAINT [FK_vcd_VcdToUsers_vcd_Users] FOREIGN KEY
        (
                [user_id]
        ) REFERENCES [dbo].[vcd_Users] (
                [user_id]
        )