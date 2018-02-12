# Updating Pervasive

Data migration from MySQL to Pervasive database is handled through special two part system


## Usage
1. MySQL maps all changes in  table
2. Fischer API triggers http://fhdev2.fischermgmt.com/API/MySqlData (Authorization: 91c547f8b9334f5d0ad799c4cfb06f8f) which processes all records and updates Pervasive tables as needed

MySQL uses
pervasivemap_table - holds all MySQL entities to be mapped
pervasivemap_field - holds all maps
    name - MySQL field to be mapped
    pervasive_table - table in Pervasive to be updated
    pervasive_field - fields in Pervasive to be updated
    pervasive_type - can be number, string or date; used to help Pervasive side parse the data
    pervasive_value - if data is in related table, we put json definition here in form of e.g. {"table":"Users", "field": "FischerUsername"}
pervasivemap_pervasivetable - maps primary key field of each Pervasive table used in mapping

After translation of data, it is inserted into system_sync table
system - currently always fischer_api, added for future extentions and systems
application - currently always site-manager, added for future extentions and systems
pervasive_database - defines which Pervasive database should be used
action - can be create, update or delete
key_fields - csv values of all Pervasive primary keys
data - json in format {"CommSiteRecordID":{"type":"number","value":9714},"Community":{"type":"string","value":"ARC"}}
created_date - self explanatory
is_processed - flag updated by Pervasive side to 1
is_errored - flag updated by Pervasive side to 1 if there was an error while processing

If this table gets too big it is safe to archive/remove all is_processed = 0 records.
Can be used to trace all changes pushed into Pervasive from MySQL.
