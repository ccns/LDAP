#!/bin/bash

Subject="CCNS社員資訊管理系統"
Receiver=$1
link=$2

mail_content=$(mktemp)

echo "CCNSer 您好："						>> $mail_content
echo "" 							>> $mail_content
echo "您已被邀請註冊CCNS社員資訊管理系統"		 	>> $mail_content
echo "請透過以下連結進入註冊頁面"		 		>> $mail_content
echo "$link" 							>> $mail_content
echo "" 							>> $mail_content
echo "如有問題請與系統管理員聯絡" 				>> $mail_content

mutt -F /home/www-data/.muttrc -s "$Subject" $Receiver < $mail_content

rm -f $mail_content
