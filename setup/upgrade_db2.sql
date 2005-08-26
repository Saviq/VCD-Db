CREATE TABLE vcd_MetaData (
        metadata_id INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1, NO CACHE), 
        record_id INTEGER NOT NULL ,
        user_id INTEGER NOT NULL ,
        metadata_name varchar (50) NOT NULL ,
        metadata_value varchar (100) NOT NULL,
        PRIMARY KEY(metadata_id)
) 