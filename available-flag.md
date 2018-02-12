# Available Flag

Site Available Flag is managed by multiple applications and stored in multiple databases.
To enable this, special API has been developed


## Usage
Pervasive is source of truth of Site Available flag.
To enable all systems to use it, we use a special API at http://fhdev2.fischermgmt.com/API/SiteAvailability/
For authentication headers should have Authorization: 91c547f8b9334f5d0ad799c4cfb06f8f
It is always a POST request with body e.g.
{
    "siteNumber" : "ARC010170000",
    "currentValue" : "1",
    "proposedValue" : "0",
    "appID" : "SiteManager",
    "status" : "new"
}

Response is always 200 and 422 errors are coded in response body like
{
    "siteNumber": "ARC010170000",
    "availableFlag": 1,
    "isEditable": 0,
    "HTTPStatus": 422
}


Request has "status" key which is used to update Sapphire Site Status.
New Sites should have status : "new", available should have it as "available" and sites on hold should have "hold" value.
