# AutoResponder

A test plugin for [Mibew 2.0](https://github.com/mibew/mibew). It tries to
answer for user's messages automatically while there is no operator in the chat.

## Installation

1. Download files of the plugin. You can use "Download ZIP" button at the
right side of the page or clone the git repository to your target machine.
2. Copy all files to ```<mibew root>/plugins/```. Thus the main plugin's file
should be situated in ```<mibew root>/plugins/Bug/Mibew/Plugin/AutoResponder/Plugin.php```
3. Update Mibew configurations by adding the following lines at the end of
```<mibew root>/libs/config.php``` file:  
```php
$plugins_list[] = array(
        'name' => 'Bug:AutoResponder',
);
```
