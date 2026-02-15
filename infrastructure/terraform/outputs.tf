output "api_endpoint" {
  description = "The URL of the events API"
  value       = "${aws_apigatewayv2_stage.default.invoke_url}/events"
}
