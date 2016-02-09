#Custom Composer packages
There are times when you want to include something in Composer that does not exist as a Composer package. This can for example be premium Wordpress plugin such as [Advanced Custom Fields Pro](http://advancedcustomfields.com) which we will use as an example here.

We have created a subdomain for few.agency that we can use internally to store such packages. Search 1P for "composer" to find the URL.

Most information in this document is based on ["Setting up your own private repository" here](http://codelight.eu/using-private-wordpress-repositories-with-composer/).

##Create custom package

0. Make sure that a package with the same version as the one you intend to create does not already exist on our Composer server.
1. Download the zip file from the ACF "store".
2. Unzip the ACF zip file.
3. Create a composer.json file with content like below. This info should be taken from official plugin documentation as much as possible (let's be nice to the original developers).

          {
            "name": "elliot-condon/advanced-custom-fields",
            "description": "Customise WordPress with powerful, professional and intuitive fields",
            "keywords": ["wordpress"],
            "homepage": "https://advancedcustomfields.com",
            "license": "GPLv2 or later",
            "authors": [
              {
                "name": "Elliot Condon",
                "email": "e@elliotcondon.com",
                "homepage": "http://www.elliotcondon.com/"
              }
            ],
            "type": "wordpress-plugin",
            "require": {
              "composer/installers": "v1.0.6"
            }
          }
          
3. Add the composer.json file to the folder with the plugin.
4. Create a zip of the folder. *Important*: when creating the zip, use the following command `zip TARGET.zip -x \*.DS_Store -r DIR_TO_ZIP/` to avoid messing up the directory structure in the zip file. When namng the zip file, it might be good to use the structure "PLUGIN_NAME_VERSION.zip" so ACF 5.2.3 would be named "advanced-custom-fields_5.2.3.zip". That way we can easily detect any existng packages on the server.
5. Upload the zip to our Composer server.
6. You have now created a composer package that we can refer to in our composer.json files for projects.

#Include custom package

AIf you were to include the ACF package created above, you would add the following snippet to repositories[] in your composer.json for the project. Make sure to update version and dist.url if needed.

          {
            "type": "package",
            "package": {
              "name": "elliot-condon/advanced-custom-fields-pro",
              "version": "5.2.3",
              "type": "wordpress-plugin",
              "dist": {
                "type": "zip",
                "url": "http://COMPOSER_SERVER/advanced-custom-fields_5.2.3.zip"
              },
              "require" : {
                "fancyguy/webroot-installer": "1.1.0"
              }
            }
          }

Finally, add the folowing line to require in comoposer.json: 
`"elliot-condon/advanced-custom-fields-pro":"5.3.4",`