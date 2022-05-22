# CIMS - 社区图书管理系统

[TOC]

### 项目介绍：

这是一个面向毕业设计的项目。

环境要求为：PHP>=7.3  mysql（或其他） composer

本项目采用Laravel（8.7.5）+layui（2.6.8）开发。

---

### 安装方式：

1. 克隆本项目到本地`git clone https://github.com/NEETLee/CIMS.git`
2. 进入项目根目录`cd CIMS`
3. 安装项目依赖`composer install`
4. 生成.env配置文件`cp .env.example .env`
5. 生成key`php artisan key:generate`
6. 修改`.env`配置文件中数据库连接配置，laravel支持的数据库均可
7. 数据库中创建`.env`配置文件中`DB_DATABASE`的数据库
8. 使用artisan生成key`php artisan key:generate`
9. 使用artisan生成数据库表和演示数据`php artisan migrate:refresh --seed`

---

### 设计需求：

1. 实现图书信息、 类别、出版社等信息的管理。
2. 实现借阅证办理以及读者信息、借阅证信息的管理。
3. 实现图书的借阅、续借和归还管理。
4. 实现图书的超期罚款管理、收款管理。
5. 实现对图书的查询功能。
6. 实现查询读者借阅图书的情况。

---

The end ~