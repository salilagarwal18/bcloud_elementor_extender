## hello

## Steps taken to develop the project.

```
npm init
```

### Installed GULP
```
npm install gulp --save-dev
```


### Useful links

https://developers.elementor.com/docs/widgets/widget-settings/
https://github.com/elementor/elementor/issues/6139

## TODO

--- Sanitize the data - Send the same request using Postman and test.
--- Resetting of Range and calculator field after form submission.


## How to set-up UnitTest for PHP

### Installed homebrew on mac
```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
Link - https://brew.sh/


## Setting unit test for JS

```
npm install --save-dev jest
```
link - https://jestjs.io/docs/getting-started


### 22 July 2022

Found the event triggered by Elementor on form submission in the file
wp-content/plugins/elementor-pro/assets/js/frontend.js - line 11200 - 11220
Found this file while searching for - elementor_pro_forms_send_form -  action 
Found this important code - 
    jQuery('.elementor-form').on('reset', function() {


