# Secure Contact Form with Prestashop 1.5 and 1.6
Prestashop module intended to stop spammers from using contact form with Google ReCaptcha

## Does not work with 1.7
Prestashop 1.7 has contact form as separate module. It cannot be overriden and thus this method will not work at all.  
It will display reCaptcha, but it will allow email being sent anyway

# Ready to install version
We always have ready to install version (via admin panel) at: [http://pl.seigi.eu/module/seigisecurecontact.html](http://pl.seigi.eu/module/seigisecurecontact.html)

## Available translations
English (default)  
Polish (translated)  
French (translated)  
**Feel free to submit translations by pull requests:)**

# How it works
- Adds dynamically reCaptcha check to contact form
- Overrides one controller/front/contactController.php

# Requirements
You need to have (or create) google account in order to obtain reCaptcha API KEYS. Without them your contact form will not work at all.  
https://www.google.com/recaptcha/admin

# Important notice
**You must remove cache/class_index.php after installation**  
Module is created on default Prestashop 1.5 theme and was tested on 1.6 theme  
**After module installation, rememmber to test your contact form! We do not take responsibility, if you do not test it**