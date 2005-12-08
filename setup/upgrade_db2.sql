CREATE TABLE vcd_MetaDataTypes (
		type_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        type_name varchar (50) NOT NULL ,
        type_description varchar (150),
        owner_id SMALLINT NOT NULL,
        PRIMARY KEY(type_id)
) 
GO

CREATE TABLE vcd_Log (
		event_id SMALLINT NOT NULL ,
		message varchar (200) NOT NULL ,
		user_id INTEGER NULL ,
		event_date DATE NOT NULL ,
		ip CHAR (15) NOT NULL 
)