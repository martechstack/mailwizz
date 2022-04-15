MailWizz - Email marketing application.  
========
    
How to install:
1. Copy vendor/ и install/
2. localhost/install/index.php and start install
3. Create db Ex leadnetwork: /Applications/MAMP/Library/bin/mysql -uroot -proot
4. First need to delete if it is exist
5. If it cannot edit config error: chmod 777 apps/common/config/main-custom.php
6. crontab -e and u can insert crons (for exit :wq)
7. Add delivery server, verify trought https://temp-mail.org/en

INSTALL STEPS: https://kb.mailwizz.com/articles/install-steps/  
(Follow these only if you install a fresh copy of the app)  

__  

GETTING STARTED STEPS: https://kb.mailwizz.com/article-categories/getting-started/    
(Follow these only if you are new to using the app)  
  
__  
      
UPGRADE STEPS: https://kb.mailwizz.com/articles/upgrade-steps/  
(Follow these only if you upgrade the app)  

__  

As always, you can get more info from www.mailwizz.com website.  
If you get stuck, please use the website to contact me.  
Thank you.  
 
---------------------------------------------------------------------------------
Yurii changes:
1) apps/common/components/mailer/MailerSwiftMailer.php - I had backuped it (MailerSwiftMailer.php_backup), had done some changes in original file, after that restored it and deleted backup 
2) apps/console/commands/SendCampaignsCommand.php - has changed (added 2059 - 2074 lines)
3) apps/common/models/DeliveryServerSmtp.php - has changed (added 56 - 61 lines)
