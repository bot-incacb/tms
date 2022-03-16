# 登录认证

使用 `JWT` 的方式进行用户登录认证，关于 `JWT` 的说明可以阅读 [JWT入门教程](https://www.ruanyifeng.com/blog/2018/07/json_web_token-tutorial.html)。

## 登录方式

1. 请求登录接口。
2. 登录成功后前端保存返回的 `access_token` 、`token_type` 以及 `expired_at` 字段，前端自行保存并维护登录状态。

## 接口认证

需要认证的接口，请求时需要传入相应的header信息用于后端验证，header结构：  
Authorization: {token_type} {access_token}  
举例：

```text
Authorization: "Bearer eyJ0eXAiOfiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvbG9naW5fcGFzc3dvcmQiLCJpYXQiOjE2Mjc0NjI0OTEsImV4cCI6MTYyNzQ2NjA5MSwibmJmIjoxNjI3NDYyNDkxLCJqdGkiOiJkQVE5OWJ2MHdlZGFFSTJvIiwic3ViIjoxLCJwcnYiOiI4NjY1YWU5Nzc1Y2YyNmY2YjhlNDk2Zjg2ZmE1MzZkNjhkZDcxODE4IiwidW5pcXVlX2tleSI6IkZObUhWTjBxeDVYRnR3NGEifQ.Ww3lTaryGjyw1arQfSElM7L8BqcoAFnZXYFQQ4e6zKc"
```

> 注意：`token_type` 首字母大写，并且 `token_type` 与 `access_token` 之间有一个空格。

## 刷新token

每次登录成功获取的token都有过期时间，即 `expired_at` 字段。  
前端每次携带token请求接口时，需要判断是否过期（当前时间是否大于或等于 `expired_at`），如果已过期则需要通过认证刷新接口重新获取新的token。  
需要注意的是，token过期时前端有可能并发多个请求，此时前端需要考虑使用 `Promise` 把所有请求先放入队列，待刷新成功之后再使用新的token执行队列请求。

## 异常处理

后端接口返回 `401` 码时，代表登录错误或者token失效，此时需要清空登录状态并提示重新登录。
