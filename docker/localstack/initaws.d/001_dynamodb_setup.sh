#!/bin/bash

set -o pipefail

echo "DynamoDB setup start"

count=$(aws --endpoint http://localhost:28000 dynamodb list-tables --query 'TableNames[] | length(@)')
# shellcheck disable=SC2181
if [ $? -ne 0 ]
then
  echo "Can't get DynamoDB tables."
  exit 1
fi

# 既にテーブル作成済みの場合は終了する
[[ "${count}" != 0 ]] && echo "DynamoDB tables already created. setup end." && exit 0

echo "DynamoDB table creation start"

# テーブルの作成。ddlディレクトリ直下のjsonファイルを対象に初期化
DYANAMO_TMP_DIR=../dynamodb
find "$DYANAMO_TMP_DIR/ddl" -name "*.json" -type f |
awk '{print "file://"$1}' |
sort |
xargs -t -I@ -P10 -n1 aws --endpoint http://localhost:28000 dynamodb create-table --cli-input-json @ 2>&1
# shellcheck disable=SC2181
if [ $? -ne 0 ]
then
  echo "Can't create tables."
  exit 1
fi

echo "creation done"

aws --endpoint http://localhost:28000 ddb put counter '{id: "users", counter: 0}'

echo "DynamoDB setup end"

exit 0
