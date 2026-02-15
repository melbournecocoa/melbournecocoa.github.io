terraform {
  required_version = ">= 1.10"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }

  backend "s3" {
    key          = "meetup-event-fetcher/terraform.tfstate"
    region       = "ap-southeast-2"
    encrypt      = true
    use_lockfile = true
  }
}

provider "aws" {
  region = var.aws_region
}

# --- Lambda ---

data "archive_file" "lambda" {
  type        = "zip"
  source_file = "${path.module}/../lambda/index.mjs"
  output_path = "${path.module}/../lambda/function.zip"
}

resource "aws_iam_role" "lambda" {
  name = "meetup-event-fetcher-lambda-role"
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Action = "sts:AssumeRole"
      Effect = "Allow"
      Principal = { Service = "lambda.amazonaws.com" }
    }]
  })
}

resource "aws_iam_role_policy_attachment" "lambda_basic" {
  role       = aws_iam_role.lambda.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AWSLambdaBasicExecutionRole"
}

resource "aws_lambda_function" "meetup_events" {
  function_name    = "meetup-event-fetcher"
  filename         = data.archive_file.lambda.output_path
  source_code_hash = data.archive_file.lambda.output_base64sha256
  handler          = "index.handler"
  runtime          = "nodejs20.x"
  timeout          = 10
  role             = aws_iam_role.lambda.arn
}

# --- API Gateway (HTTP API) ---

resource "aws_apigatewayv2_api" "events" {
  name          = "meetup-events-api"
  protocol_type = "HTTP"

  cors_configuration {
    allow_origins = ["https://melbournecocoaheads.com"]
    allow_methods = ["GET"]
    allow_headers = ["Content-Type"]
  }
}

resource "aws_apigatewayv2_stage" "default" {
  api_id      = aws_apigatewayv2_api.events.id
  name        = "$default"
  auto_deploy = true
}

resource "aws_apigatewayv2_integration" "lambda" {
  api_id                 = aws_apigatewayv2_api.events.id
  integration_type       = "AWS_PROXY"
  integration_uri        = aws_lambda_function.meetup_events.invoke_arn
  payload_format_version = "2.0"
}

resource "aws_apigatewayv2_route" "get_events" {
  api_id    = aws_apigatewayv2_api.events.id
  route_key = "GET /events"
  target    = "integrations/${aws_apigatewayv2_integration.lambda.id}"
}

resource "aws_lambda_permission" "apigw" {
  statement_id  = "AllowAPIGatewayInvoke"
  action        = "lambda:InvokeFunction"
  function_name = aws_lambda_function.meetup_events.function_name
  principal     = "apigateway.amazonaws.com"
  source_arn    = "${aws_apigatewayv2_api.events.execution_arn}/*/*"
}
