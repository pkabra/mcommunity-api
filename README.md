#MCommunity JSON API

A simple API that connects to the MCommunity LDAP and returns information that is publicly available on MCommunity in JSON format to use in whatever way you can imagine.

##The Usage

Requests can be sent to the api very easily.
For example:

> `http://kabra.com/mcommunity/?uniqname=pkabra`

> `http://kabra.com/mcommunity/?firstname=P*&surname=Kabra`

Will return information, that is publicly available, about me.

##Request Attributes
Only publicly available information can be requested from the API.
You can request the following data through GET.

The following variables will always contain data.

- `uniqname` for Uniqname
- `firstname` for first name
- `surname` for last name
- `mail` for umich email address (not much use)

The following variables may not contain data.
As such if you query them, the return may exclude some entries. (Look at considerations)

- `address` for home address (Note: Could be private)
- `workaddress` for work address
- `phone` for telephone number
- `mobile` for mobile number (Note: Could be private)

##Return Attributes
Data will be returned in JSON format.

Two attributes are returned in the first level of JSON.

- `count` which is the number of entries returned
- `data` which contains the returned entries

The following information is returned in the `data` attribute, if available. Note, if data is restricted or contains nothing it will not return.

- `uniqname` for Uniqname
- `fullname` for full name
- `firstname` for first name
- `surname` for last name
- `mail` for umich email address (not much use)
- `address` for home address (Note: Could be private)
- `workaddress` for work address
- `phone` for telephone number
- `mobile` for mobile number (Note: Could be private)

##Considerations

Any requested data that is queried acts as an AND statement. Therefore entries may be excluded. For example `uniqname=pkabra&firstname=John` will return no data as no entry will be found with both Uniqname=pkabra and Firstname=John (that uniqname is mine and my firstname is Pratik, not John).

Private data is equivilant to that data not existing. Therefore if I query my address, there will be no returned data as my home address is private.

Wildcards can be used. Example `firstname=p*` or `firstname=*p` or even `firstname=*p*`.

##Limits

- Active directory will only return a maximum of 350 entries to anonymous users. Your query may return even more results, but you will only receive the first entries.

- UMIDs are restricted data, reserved only for administrative users. Therefore you cannot query UMIDs.

##Dependencies

- Requires Apache `mod_ldap`
- Requires `php_ldap`
- Requires `php_json`