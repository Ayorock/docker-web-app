provider "aws" {
  region = "us-east-1"
  access_key = "AWS_ACCESS_KEY_ID"
  secret_key = "AWS_SECRET_ACCESS_KEY"
}

resource "aws_dynamodb_table" "guest_list" {
  name           = "GuestList"
  billing_mode   = "PAY_PER_REQUEST"
  hash_key       = "Username"

  attribute {
    name = "Username"
    type = "S"
  }

  tags = {
    Name        = "GuestList"
    Environment = "Production"
  }
}

resource "aws_dynamodb_table_item" "guest_1" {
  table_name = aws_dynamodb_table.guest_list.name
  hash_key   = "Username"
  item       = <<ITEM
{
  "Username": {"S": "user1"},
  "Password": {"S": "password1"},
  "Email": {"S": "user1@example.com"}
}
ITEM
}

resource "aws_dynamodb_table_item" "guest_2" {
  table_name = aws_dynamodb_table.guest_list.name
  hash_key   = "Username"
  item       = <<ITEM
{
  "Username": {"S": "user2"},
  "Password": {"S": "password2"},
  "Email": {"S": "user2@example.com"}
}
ITEM
}

resource "aws_dynamodb_table_item" "guest_3" {
  table_name = aws_dynamodb_table.guest_list.name
  hash_key   = "Username"
  item       = <<ITEM
{
  "Username": {"S": "user3"},
  "Password": {"S": "password3"},
  "Email": {"S": "user3@example.com"}
}
ITEM
}
