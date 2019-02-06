VERSION HISTORY
-----

2019-02-06 WED:
[PTS]
- v0.12.0:
    - BREAKING CHANGE
        - Renamed TimUtil.getDBTimeZone to "TimeUtil.getDbTimezone" 
            (note the case of "DB" has changed to "Db").
            
    - Added fromCarbonToDate to TimeUtil.
    
    - Added \Logistio\Symmetry\Exception\FlaggedExceptionFactory
    
    - Added tests:
        - \Logistio\Symmetry\Test\PublicId\PublicIdManagerTest
        - \Logistio\Symmetry\Test\PublicId\PublicIdConverterTest


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
          
          

           

