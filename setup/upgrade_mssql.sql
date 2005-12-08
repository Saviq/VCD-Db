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
