studyabc 英语学习社区
============================
#关于框架说明
* 本次开发没有选用自己开发的[phpframe-simple](https://github.com/apanly/phpframe-simple),在开发过程中发现，每次开发一个新的系统就要把框架复制一份，觉得非常不好，所以想到多app的框架.但是phpframe-simple非常适合一次性交付的系统,例如外包系统等等
* 新框架：架构基本是和我公司(上海安居客)的框架一样
* 自动化部署可以参考 https://github.com/apanly/ansible-yyabc

# 依赖数据job
## 1 每日获取有道的微单词
10 9 * * * /usr/local/php5/bin/php /var/chroot/home/content/28/12084728/html/luancher.php tinyenglish.php sentence   > /var/chroot/home/content/28/12084728/html/crontab.log
## 2 获取双语阅读
30 9 * * * /usr/local/php5/bin/php /var/chroot/home/content/28/12084728/html/luancher.php  engchi.php
## 4 微信用户信息抓取
0,30 * * * * /usr/local/php5/bin/php /var/chroot/home/content/28/12084728/html/book/aa.php  wxcrontab getUserInfo
## 3 获取微信信息并建立关系表
0,30 * * * * /usr/local/php5/bin/php /var/chroot/home/content/28/12084728/html/book/aa.php  wxcrontab default
## 5 十句话
5 9 * * * /usr/local/php5/bin/php /var/chroot/home/content/28/12084728/html/luancher.php   juhua10.php


#nginx
server{
    listen 80;
    server_name studyabc.com;
    root /home/vincent/opensource/simpleenglish;
    location / {
       index  index.html index.php;
       rewrite ^/(.*)$ /index.php;
    }
    location ~ ^/images {
         root /home/vincent/opensource/simpleenglish/app-static/view/;
    }
    location ~ \.php$ {
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include  fastcgi_params;
    }
}


