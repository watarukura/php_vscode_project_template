#!/bin/bash

cat <<EOF > ddl.json
{
  "AttributeDefinitions": [
    {
      "AttributeName": "id",
      "AttributeType": "S"
    }  ],
  "KeySchema": [
    {
      "AttributeName": "id",
      "KeyType": "HASH"
    }
  ],
  "BillingMode": "PAY_PER_REQUEST",
  "TableName": "users"
}

EOF

aws dynamodb create-table --cli-input-json file://ddl.json --endpoint-url http://localhost:8000

rm ddl.json