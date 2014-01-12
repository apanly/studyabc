studyabc 英语学习社区
============================
#关于框架说明
* 本次开发没有选用自己开发的[phpframe-simple](https://github.com/apanly/phpframe-simple),在开发过程中发现，每次开发一个新的系统就要把框架复制一份，觉得非常不好，所以想到多app的框架.但是phpframe-simple非常适合一次性交付的系统,例如外包系统等等
* 新框架：架构基本是和我公司(上海安居客)的框架一样
* 自动化部署可以参考 https://github.com/apanly/ansible-yyabc
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


