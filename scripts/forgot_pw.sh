#!/bin/bash

Subject="重設密碼通知"
Receiver=$2

mail_content=$(mktemp)

name=$1
tmp_pw=$3
date=$(date +"%F %T")

echo "使用者 $1 您好：" 					>> $mail_content
echo "" 							>> $mail_content
echo "您於 $date 使用了CCNS社員資訊管理系統的忘記密碼功能" 	>> $mail_content
echo "系統將指派一組暫時密碼讓您登入並重新設定密碼" 		>> $mail_content
echo "" 							>> $mail_content
echo "暫時密碼為 $tmp_pw" 					>> $mail_content
echo "" 							>> $mail_content
echo "請儘速登入並重新設定您的密碼" 				>> $mail_content
echo "如有問題請與系統管理員聯絡" 				>> $mail_content

mutt -F /home/www-data/.muttrc -s "$Subject" $Receiver < $mail_content

rm -f $mail_content
