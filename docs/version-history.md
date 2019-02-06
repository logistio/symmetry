VERSION HISTORY
-----

2019-02-06 WED:
[PTS]
- v0.13.0:
    - BREAKING CHANGE
        - Renamed TimUtil.getDBTimeZone to "TimeUtil.getDbTimezone" 
            (note the case of "DB" has changed to "Db").
            
    - Added reset function to PublicIdManager.
            
    - Added fromCarbonToDate to TimeUtil.
    
    - Added \Logistio\Symmetry\Exception\FlaggedExceptionFactory
    
    - Fixed errors in \Logistio\Symmetry\Util\Time\DateRange.
        - Weekly ranges were producing open intervals, instead of the expected
            half-open intervals, according to the class's tests.
    
    - Added tests:
        - \Logistio\Symmetry\Test\PublicId\PublicIdManagerTest
        - \Logistio\Symmetry\Test\PublicId\PublicIdConverterTest


2018-11-29 THU:
[DP]
- Added the `ProcessClientState` interface.
- Added a base `toArray()` method on the `ProcessPayload` class.

2018-11-19 MON:
[DP]
- Changed the `ColumnOrder` instance variables to be public. 
- Added method to get all db columns to order by. 

2018-11-09 FRI:
[DP]
- Changed `processLogs` relation of the `Process` class to order by created_at and id in ascending order. 

2018-10-31 WED:
[DP]
- v0.12.3
    - Modified the HttpRequestAgentFactory to consume the `Symmetry-Device-Screen` header instead of the cookie.
- v0.12.2
    - Modified the CORS Service to set the request origin to the 'Access-Control-Allow-Origin' header,
    rather than relying on the * wildcard.
- v0.12.1
    - Removed return types from HttpRequestAgent.
- v0.12.0
    - Added the HttpRequestAgent class to store the request agent details.
    - Added the `jenssegers/agent` package.

2018-10-10 WED:
[DP]
- v0.11.3
    - Refactored `ProcessQueryRequestDecorator` to add the API column code tags.


2018-10-08 MON:
[PTS]
- v0.11.1: 
    - Removed "version" from "composer.json.".
        - Having a "version" tag in the json is known to cause errors,
          and introduces redundancy into the release tagging process.
          When no "version" is found in the JSON it is only necessary to 
          tag a commit in order to make a release. If there is "version"
          included in the json then one must also update the version in order
          to create a release, and if one forgets to update the version then
          it can cause errors when trying to access the previous version,
          as the tag and the json version no longer agree.
          The Composer documentation recommends that this is omitted:
          https://getcomposer.org/doc/04-schema.md#version
          
          

           

