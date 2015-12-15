# Repxthx

Repxthx is an extension for [MediaWiki][mw] that provides a thanks-based reputation and credit values for users and quality for pages.

The algorithm used for calculations is based on the paper [Network-Driven Reputation in Online Scientific Communities][algPaper].

## Installation

- Create a directory named repxthx into /extensions 
- Clone the repository and copy the content of src/ into extensions/repxthx
- Add the following code at the bottom of your LocalSettings.php
    ```php
    require_once "$IP/extensions/repxthx/ReptxThx.php";
    ```
- Run the [update script][upsmw] which will automatically create the necessary database tables that this extension needs.
- Navigate to Special:Version on your wiki to verify that the extension is successfully installed.

**Important**: Repxthx requires the [Thanks extension][thanksEx] be installed as a prerequisite.

## Configuration

The algorithm used for the calculations of the reputation, credit and quality values allows to customize some parameters which can be used to adjust the algorithm to every different communities. There are also two parameters that defines the weigth of the thank interactions .All this parameters can be found at repxthx/ReptxThx.php.
```php
$giveThankWeight = 0.1;
$receiveThankWeight = 0.8;

$tetaR = 1;
$tetaF = 1;
$phiA = 1;
$phiP = 1;
$roF = 0.5;
$roR = 1;
$lambda = 1;
```

For more information about the meaning of each parameter read [Network-Driven Reputation in Online Scientific Communities][algPaper].

to configure the frecuency of execution of the calculations you can set the number of user interactions needed for the job of execution to be added into the Job Queue. The parameter used for this can be found at repxthx/ReptxThx.php.
```php
$executionInteractionCount = 1;
```

> The overriding design goal for Markdown's
> formatting syntax is to make it as readable
> as possible. The idea is that a
> Markdown-formatted document should be
> publishable as-is, as plain text, without
> looking like it's been marked up with tags
> or formatting instructions.

This text you see here is *actually* written in Markdown! To get a feel for Markdown's syntax, type some text into the left window and watch the results in the right.

## Usage

This extension implements methods for the MediaWiki API and extends the Wikicode markup langage.

### API Methods
You can call this methods to get some data in JSON format.

- Getting user reputation and credit values: wiki_url/api.php?action=query&list=reptxthxuser&rxtuuserName=<user_name>&rxtuuserId=<user_id>
- Getting page quality value: wiki_url/api.php?action=query&list=reptxthxpage&rxtppageTitle=<page_title>&rxtppageId=<page_id>
    
### Wikicode

Adding these while editing a page prints information for users.

- Printing user reputation: {{#rept: <user_name>}}
- Printing user credit: {{#cred: <user_name>}}
## License

GNU GENERAL PUBLIC LICENSE Version 3

   [upsmw]: <https://www.mediawiki.org/wiki/Manual:Update.php>
   [algPaper]:<http://journals.plos.org/plosone/article?id=10.1371/journal.pone.0112022>
   [thanksEx]: <https://www.mediawiki.org/wiki/Extension:Thanks/es>
   
