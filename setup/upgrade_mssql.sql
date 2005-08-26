CREATE TABLE [vcd_MetaData] (
	[metadata_id] [int] IDENTITY (1, 1) NOT NULL ,
	[record_id] [int] NOT NULL ,
	[user_id] [int] NOT NULL ,
	[metadata_name] [varchar] (50) NOT NULL ,
	[metadata_value] [varchar] (100) NOT NULL
) ON [PRIMARY]

ALTER TABLE [dbo].[vcd_MetaData] ADD 
	CONSTRAINT [FK_vcd_MetaData_vcd_Users] FOREIGN KEY 
	(
		[user_id]
	) REFERENCES [dbo].[vcd_Users] (
		[user_id]
	)