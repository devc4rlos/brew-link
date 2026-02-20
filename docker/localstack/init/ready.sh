#!/bin/bash
echo "----------- Creating Local AWS Resources -----------"

BUCKET_NAME=${AWS_BUCKET:-default-bucket}
TABLE_NAME=${AWS_DYNAMODB_TABLE_NAME:-default-table}

echo "Creating bucket: $BUCKET_NAME"
awslocal s3 mb s3://"$BUCKET_NAME"

echo "Creating table: $TABLE_NAME"
awslocal dynamodb create-table \
    --table-name "$TABLE_NAME" \
    --key-schema AttributeName=short_code,KeyType=HASH \
    --attribute-definitions AttributeName=short_code,AttributeType=S \
    --billing-mode PAY_PER_REQUEST

echo "----------- Resources created successfully -----------"