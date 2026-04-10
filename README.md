# アプリケーション名

timelog

## 環境構築

Dockerビルド  
・git clone git@github.com:yuki-nakagawa-1021/timelog.git  
・docker-compose up -d --build

### Laravel環境構築

・docker-compose exec php bash  
・composer install  
・cp .env.example .env  
・php artisan key:generate  
・php artisan migrate  
・php artisan db:seed

## メール認証について

本アプリではメール認証にMailHogを使用しています。  
認証メールは実際のメールアドレスには送信されず、MailHog上で確認できます。

MailHog : http://localhost:8025

## Stripe設定

APIキーの設定は各自でお願いします。

## 開発環境

・会員登録画面（一般ユーザー）：/register  
・ログイン画面（一般ユーザー）：/login  
・勤怠登録画面（一般ユーザー）：/attendance  
・勤怠一覧画面（一般ユーザー）：/attendance/list  
・勤怠詳細画面（一般ユーザー）：/attendance/detail/{id}  
・申請一覧画面（一般ユーザー）：/stamp_correction_request/list  
・ログイン画面（管理者）：/admin/login  
・勤怠一覧画面（管理者）：/admin/attendance/list  
・勤怠詳細画面（管理者）：/admin/attendance/{id}  
・スタッフ一覧画面（管理者）：/admin/staff/list  
・スタッフ別勤怠一覧画面（管理者）：/admin/attendance/staff/{id}  
・申請一覧画面（管理者）：/stamp_correction_request/list  
・修正申請承認画面（管理者）：/stamp_correction_request/approve/{attendance_correct_request_id}  
・phpMyAdmin：http://localhost:8080/  
・MailHog：http://localhost:8025

## 使用技術（実行環境）

・PHP 8.2.30  
・Laravel 8.83.29  
・mysql 8.0.26  
・nginx 1.21.1  
・MailHog  
・Stripe  
・JavaScript

## 主な機能

## ER図

![ER図](ER図.svg)

## URL

開発環境：http://localhost/
