VERSION HISTORY
-----

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
          
          

           

